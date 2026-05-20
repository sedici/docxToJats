<?php namespace docx2jats\jats;

/**
 * @file src/docx2jats/jats/LinkTypeHelper.php
 *
 * Copyright (c) 2025 Vitalii Bezsheiko
 * Distributed under the GNU GPL v3.
 *
 * @brief Helper to infer ext-link-type from URL
 */

class LinkTypeHelper {

	/**
	 * Infers the ext-link-type based on the URL.
	 * Returns null if no specific type is detected and it's not a standard URI/FTP.
	 *
	 * @param string $url
	 * @return string|null
	 */
	public static function inferType(string $url): ?string {
		$url = trim($url);
		
		if (empty($url)) {
			return null;
		}

		// Check for specific protocols
		if (strpos($url, 'ftp://') === 0) {
			return 'ftp';
		}


		// Check for DOI
		if (preg_match('/^10\.\d{4,9}\/[-._;()\/:A-Z0-9]+$/i', $url) || strpos($url, 'doi.org') !== false) {
			return 'doi';
		}

		// Check for specific domains or patterns
		if (strpos($url, 'chem.qmw.ac.uk/iubmb/enzyme') !== false) {
			return 'ec';
		}
		if (strpos($url, 'ncbi.nlm.nih.gov/genbank') !== false) {
			return 'gen';
		}
		if (strpos($url, 'ncbi.nlm.nih.gov/protein') !== false) {
			return 'genpept';
		}
		if (strpos($url, 'pdb.org') !== false || strpos($url, 'rcsb.org') !== false) {
			return 'pdb';
		}
		if (strpos($url, 'ebi.ac.uk/~textman/pgr-htdocs') !== false) {
			return 'pgr';
		}
		if (strpos($url, 'pir.georgetown.edu') !== false) {
			return 'pir';
		}
		if (strpos($url, 'ncbi.nlm.nih.gov/pmc') !== false) {
			return 'pmcid';
		}
		if (strpos($url, 'ncbi.nlm.nih.gov/pubmed') !== false) {
			return 'pmid';
		}
		if (strpos($url, 'uniprot.org') !== false || strpos($url, 'ebi.ac.uk/swissprot') !== false) {
			return 'sprot';
		}

		// Default to uri only if it looks like a web URL
		if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) {
			return 'uri';
		}

		return 'uri';
	}
}
