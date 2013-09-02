<?php namespace Torann\Cells;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Environment;
use Illuminate\Config\Repository;

abstract class CellBaseController {

	/**
	 * Environment view.
	 *
	 * @var \Illuminate\View\Environment
	 */
	protected $view;

	/**
	 * Default attributes.
	 *
	 * @var array
	 */
	public $attributes = array();

	/**
	 * Default cache value.
	 *
	 * @var string
	 */
	public $cache = 0;

	/**
	 * Create a new instance.
	 *
	 * @param  \Illuminate\Config\Repository $view
	 * @return void
	 */
	public function __construct(Environment $view)
	{
		$this->view = $view;
	}

	/**
	 * Abstract class init for a cell factory.
	 *
	 * @return void
	 */
	//abstract public function init();

	/**
	 * Abstract class run for a cell factory.
	 *
	 * @return void
	 */
	abstract public function run();

	/**
	 * Set attributes to object var.
	 *
	 * @param  arary  $attributes
	 * @return void
	 */
	public function setAttributes($attributes)
	{
		$this->attributes = array_merge($this->attributes, $attributes);
	}

	/**
	 * Set attribute.
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function setAttribute($key, $value)
	{
		$this->attributes[$key] = $value;
	}

	/**
	 * Get attributes.
	 *
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * Get attribute with a key.
	 *
	 * @param  string  $key
	 * @param  string  $default
	 * @return mixed
	 */
	public function getAttribute($key, $default = null)
	{
		return array_get($this->attributes, $key, $default);
	}

	/**
	 * Start cell factory.
	 *
	 * @return void
	 */
	public function beginCell()
	{
		$this->init();
	}

	/**
	 * End cell factory.
	 *
	 * @return void
	 */
	public function endCell()
	{
		$data = (array) $this->run();

		$this->data = array_merge($this->attributes, $data);
	}

	/**
	 * Display cell HTML.
	 *
	 * @return string
	 */
	public function display()
	{
		$path = "$this->name.display";

		if($this->cache)
		{
			return Cache::remember("Cells.$path", $this->cache, function() use ($path) {
				return CellBaseController::render($path);
			});
		}

		return $this->render($path);
	}

	/**
	 * Render cell to HTML.
	 *
	 * @return string
	 */
	public function render($path)
	{
		if ( ! $this->view->exists($path))
		{
			throw new UnknownCellsFileException("Cell view [$this->name] not found.");
		}

		return (string) $this->view->make($path, $this->data);
	}

}