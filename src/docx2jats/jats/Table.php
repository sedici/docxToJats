<?php namespace docx2jats\jats;

/**
 * @file src/docx2jats/jats/Table.php
 *
 * Copyright (c) 2018-2020 Vitalii Bezsheiko
 * Distributed under the GNU GPL v3.
 *
 * @brief representі JATS XML table
 */

use docx2jats\objectModel\DataObject;
use docx2jats\jats\Row as JatsRow;

class Table extends Element {

	const JATS_TABLE_ID_PREFIX = 'tbl';

	public function __construct(DataObject $dataObject) {
		parent::__construct($dataObject);
	}

	public function setContent() {
		/**
		 * TODO Table, Figure and Formula have the same caption, id and label elements handling,
		 * consider ancestor class or trait
		 */
		$dataObject = $this->getDataObject(); /* @var $dataObject \docx2jats\objectModel\body\Table */

		if ($dataObject->getId()) {
			$this->setAttribute('id', self::JATS_TABLE_ID_PREFIX . $dataObject->getId());
		}

		if ($dataObject->getLabel()) {
			$this->appendChild($this->ownerDocument->createElement('label', $dataObject->getLabel()));
		}

		if ($dataObject->getTitle()) {
			$captionNode = $this->ownerDocument->createElement('caption');
			$this->appendChild($captionNode);
			$title = $this->ownerDocument->createElement('title', $dataObject->getTitle());
			// append citation if exists
			if ($dataObject->hasReferences()) {
				$refIds = $dataObject->getRefIds();
				$lastKey = array_key_last($refIds);
				foreach ($refIds as $key => $id) {
					$refEl = $this->ownerDocument->createElement('xref', $id);
					$refEl->setAttribute('ref-type', 'bibr');
					$refEl->setAttribute('rid', Reference::JATS_REF_ID_PREFIX . $id);
					if ($key !== $lastKey) {
						$empty = $this->ownerDocument->createTextNode(' ');
						$title->appendChild($empty);
					}
					$title->appendChild($refEl);
	            }
	        }

			$captionNode->appendChild($title);
		}

		$tableNode = $this->ownerDocument->createElement('table');
		$this->appendChild($tableNode);

		// Separate header rows from body rows
		$headerRows = array();
		$bodyRows = array();
		
		foreach ($dataObject->getContent() as $content) {
			/* @var $content \docx2jats\objectModel\body\Row */
			if ($content->isHeader()) {
				$headerRows[] = $content;
			} else {
				$bodyRows[] = $content;
			}
		}

		// Create thead if there are header rows
		if (!empty($headerRows)) {
			$theadNode = $this->ownerDocument->createElement('thead');
			$tableNode->appendChild($theadNode);

			foreach ($headerRows as $content) {
				$row = new JatsRow($content);
				$theadNode->appendChild($row);
				$row->setContent();
			}
		}

		// Create tbody element as required by JATS 1.1 (SPS 1.9)
		// tbody is always created even if there are no body rows (though that would be unusual)
		$tbodyNode = $this->ownerDocument->createElement('tbody');
		$tableNode->appendChild($tbodyNode);

		// Add all body rows inside tbody
		foreach ($bodyRows as $content) {
			$row = new JatsRow($content);
			$tbodyNode->appendChild($row);
			$row->setContent();
		}
	}
}
