<?php
/***************************************************************************
 *   Copyright (C) 2006 by Anton E. Lebedevich                             *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/
/* $Id$ */

	/**
	 * @ingroup Flow
	**/
	class PhpViewResolver implements ViewResolver
	{
		private $prefix		= null;
		private $postfix	= null;
		
		public function __construct($prefix = null, $postfix = null)
		{
			$this->prefix	= $prefix;
			$this->postfix	= $postfix;
		}
		
		public function resolveViewName($viewName)
		{
			return
				new SimplePhpView(
					$this->prefix.$viewName.$this->postfix,
					$this
				);
		}
		
		public function getPrefix()
		{
			return $this->prefix;
		}
		
		public function setPrefix($prefix)
		{
			$this->prefix = $prefix;
			
			return $this;
		}
		
		public function getPostfix()
		{
			return $this->postfix;
		}
		
		public function setPostfix($postfix)
		{
			$this->postfix = $postfix;
			
			return $this;
		}
	}
?>