<?php
/**
 * Class to perform a commit log search
 *
 * @author Christopher Han <xiphux@gmail.com>
 * @copyright Copyright (c) 2012 Christopher Han
 * @package GitPHP
 * @subpackage Git
 */
class GitPHP_CommitSearch extends GitPHP_RevList
{
	/**
	 * Commit search type
	 *
	 * @const
	 */
	const CommitType = 1;

	/**
	 * Author search type
	 *
	 * @const
	 */
	const AuthorType = 2;

	/**
	 * Committer search type
	 *
	 * @const
	 */
	const CommitterType = 3;

	/**
	 * Stores the search type
	 *
	 * @var int
	 */
	protected $type;

	/**
	 * Stores the search query
	 *
	 * @var string
	 */
	protected $search;

	/**
	 * Constructor
	 *
	 * @param GitPHP_Project $project project
	 * @param int $type search type
	 * @param string $search search string
	 * @param GitPHP_Commit $head head to walk back from
	 * @param int $limit limit of revisions to walk
	 * @param int $skip number of revisions to skip
	 */
	public function __construct($project, $type, $search, $head = null, $limit = 50, $skip = 0)
	{
		parent::__construct($project, $head, $limit, $skip);

		if (!$type) {
			throw new Exception('Search type is required');
		}

		if (empty($search)) {
			throw new Exception('Search string is required');
		}

		$this->type = $type;
		$this->search = $search;
	}

	/**
	 * Gets the search type
	 *
	 * @return int search type
	 */
	public function GetType()
	{
		return $this->type;
	}

	/**
	 * Sets the search type
	 *
	 * @param int $type search type
	 */
	public function SetType($type)
	{
		if (!$type)
			return;

		if ($type == $this->type)
			return;

		if ($this->dataLoaded)
			$this->Clear();

		$this->type = $type;
	}

	/**
	 * Gets the search query
	 *
	 * @return string search query
	 */
	public function GetSearch()
	{
		return $this->search;
	}

	/**
	 * Sets the search query
	 *
	 * @param string $search search query
	 */
	public function SetSearch($search)
	{
		if (empty($search))
			return;

		if ($this->search == $search)
			return;

		if ($this->dataLoaded)
			$this->Clear();

		$this->search = $search;
	}

	/**
	 * Loads the data for this search
	 */
	protected function LoadData()
	{
		$this->dataLoaded = true;

		$args = array();

		if (GitPHP_GitExe::GetInstance()->CanIgnoreRegexpCase())
			$args[] = '--regexp-ignore-case';

		switch ($this->type) {
			case GitPHP_CommitSearch::CommitType:
				$args[] = '--grep="' . addslashes($this->search) . '"';
				break;
			case GitPHP_CommitSearch::AuthorType:
				$args[] = '--author="' . addslashes($this->search) . '"';
				break;
			case GitPHP_CommitSearch::CommitterType:
				$args[] = '--committer="' . addslashes($this->search) . '"';
				break;
		}

		$this->hashList = $this->RevList($args);
	}

}