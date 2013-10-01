<?php

abstract class PDFXObject {
	/**
	 * Give a string value of the name of this XObject
	 * @return string
	 */
	public abstract function getName();
	
	/**
	 * Append the XObject to the file ans returns the main referance
	 * @param PDFFile $file
	 * @return PDFObject
	 */
	public abstract function append($file);
}