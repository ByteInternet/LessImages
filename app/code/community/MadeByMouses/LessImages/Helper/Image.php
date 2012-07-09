<?php

/**
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category    MadeByMouses
 * @package     MadeByMouses_LessImages
 * @copyright   Copyright (c) 2012 Made By Mouses <info@madebymouses.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adds specific class to top level elements
 * 
 * @author Made by Mouses
 */
class MadeByMouses_LessImages_Helper_Image extends Mage_Catalog_Helper_Image {
	/**
	 * Return Image URL
	 * Original file url will be returned when no resizing, rotation
	 * or watermark is applied.
	 *
	 * @return string
	 */
	public function __toString() {
		try {
			if($this -> getImageFile()) {
				$this -> _getModel() -> setBaseFile($this -> getImageFile());
			} else {
				$this -> _getModel() -> setBaseFile($this -> getProduct() -> getData($this -> _getModel() -> getDestinationSubdir()));
			}

			if($this -> _getModel() -> isCached()) {
				return $this -> _getModel() -> getUrl();
			} else {
				if(!$this -> _scheduleRorate && !$this -> _scheduleResize && !$this -> getWatermark()) {
					$baseDir = Mage::getBaseDir('media');
					$path = str_replace($baseDir . DS, "", $this -> _getModel() -> getBaseFile());
					return Mage::getBaseUrl('media') . str_replace(DS, '/', $path);
				} else {
					if($this -> _scheduleRotate) {
						$this -> _getModel() -> rotate($this -> getAngle());
					}

					if($this -> _scheduleResize) {
						$this -> _getModel() -> resize();
					}

					if($this -> getWatermark()) {
						$this -> _getModel() -> setWatermark($this -> getWatermark());
					}

					$url = $this -> _getModel() -> saveFile() -> getUrl();
				}
			}
		} catch(Exception $e) {
			$url = Mage::getDesign() -> getSkinUrl($this -> getPlaceholder());
		}

		return $url;
	}
}