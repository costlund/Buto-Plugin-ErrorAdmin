<?php
class PluginErrorAdmin{
  private $plugin_settings = null;
  private $mysql = null;
  function __construct(){
    wfPlugin::enable('bootstrap/modal');
    wfPlugin::includeonce('wf/yml');
    wfPlugin::includeonce('wf/mysql');
    /**
     * Settings
     */
    $this->plugin_settings = wfPlugin::getPluginSettings('error/admin', true);
    /**
     * mysql
     */
    $this->mysql = new PluginWfMysql();
    $this->mysql->event = false;
  }
  public function widget_panel(){
    /**
     * chartjs
     */
    $rs = $this->db_chart_date();
    $element = new PluginWfYml(__DIR__.'/element/chartjs.yml');
    $datasets = new PluginWfArray();
    $datasets->set('0/label', 'Warning');
    $datasets->set('0/data', null);
    $datasets->set('1/label', 'Error');
    $datasets->set('1/data', null);
    $datasets->set('2/label', 'Others');
    $datasets->set('2/data', null);
    foreach($rs as $k => $v){
      $datasets->set('0/data/', array('x' => $v['error_date'], 'y' => $v['error_warning']));
      $datasets->set('1/data/', array('x' => $v['error_date'], 'y' => $v['error_error']));
      $datasets->set('2/data/', array('x' => $v['error_date'], 'y' => $v['error_others']));
    }
    $element->setByTag(array('data' => $datasets->get()));
    wfDocument::renderElement(array($element->get()));
    /**
     * table
     */
    $element = new PluginWfYml(__DIR__.'/element/panel.yml');
    wfDocument::renderElement($element);
  }
  private function role_check(){
    if(wfUser::hasRole('unknown')){
      exit('Unknown user.');
    }
    return null;
  }
  public function page_list_data(){
    $this->role_check();
    $rs = $this->db_list();
    wfPlugin::includeonce('datatable/datatable_1_10_18');
    $datatable = new PluginDatatableDatatable_1_10_18();
    exit($datatable->set_table_data($rs));
  }
  private function db_sql($key){
    return new PluginWfYml(__DIR__.'/mysql/sql.yml', $key);
  }
  private function db_open(){
    $this->mysql->open($this->plugin_settings->get('settings/mysql'));
  }
  private function db_list(){
    $this->db_open();
    $sql = $this->db_sql(__FUNCTION__);
    $this->mysql->execute($sql->get());
    return $this->mysql->getMany();
  }
  private function db_chart_date(){
    $this->db_open();
    $sql = $this->db_sql(__FUNCTION__);
    $this->mysql->execute($sql->get());
    return $this->mysql->getMany();
  }
}
