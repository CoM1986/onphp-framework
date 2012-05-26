<?php
/***************************************************************************
 *   Copyright (C) 2012 by Timofey A. Anisimov                             *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

	final class SlaveDBPool
	{
		private $pool = array();
		private $weightGrade = array();

		public static function create()
		{
			return new self;
		}

		public function addLink($name, DB $db, $weight = 1)
		{
			if (isset($this->pool[$name]))
				throw new WrongArgumentException(
					"already have '{$name}'  slave link"
				);

			Assert::isPositiveInteger($weight);

			$this->pool[$name] = $db;
			$this->weightGrade =
				array_merge(
					$this->weightGrade,
					array_fill(0, $weight, $name)
				);

			return $this;
		}

		public function dropLink($name)
		{
			if (!isset($this->pool[$name]))
				throw new MissingElementException(
					"link '{$name}' not found"
				);

			unset($this->pool[$name]);

			$this->weightGrade =
				array_filter(
					$this->weightGrade,
					function ($value) use ($name) {
						return $name != $value;
					}
				);

			return $this;
		}

		public function getSlaveLink()
		{
			while ($this->pool) {
				$name = array_rand($this->weightGrade);
				$link = $this->pool[$name];

				if (!$link->isConnected())
					try {
						$link->connect();
					} catch (DatabaseException $e) {
						$this->dropLink($name);
						continue;
					}

				return $link;
			}

			return null;
		}
	}
