<?php namespace JeffLemon\Privilege;
 
use Illuminate\Support\Facades\Facade;
 
class PrivilegeFacade extends Facade {
 
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor() { return 'privilege'; }
 
}