<?php namespace docx2jats\jats;

/**
 * @file src/docx2jats/jats/Row.php
 *
 * Copyright (c) 2018-2019 Vitalii Bezsheiko
 * Distributed under the GNU GPL v3.
 *
 * @brief represent JATS XML tables' row
 */

use docx2jats\objectModel\DataObject;
use docx2jats\jats\Cell as JatsCell;

class Row extends Element {
	public function __construct(DataObject $dataObject) {
		parent::__construct($dataObject);
	}

	public function setContent() {
		$dataObject = $this->getDataObject(); /* @var $dataObject \docx2jats\objectModel\body\Row */
		$isHeaderRow = $dataObject->isHeader();
		
		foreach ($dataObject->getContent() as $content) {
			$cell = new JatsCell($content, $isHeaderRow);
			$this->appendChild($cell);
			$cell->setContent();
		}
	}
}
