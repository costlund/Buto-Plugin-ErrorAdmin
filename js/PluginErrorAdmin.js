function PluginErrorAdmin(){
  this.data = {log: null}
  this.test = function(){
    console.log(this.data);
  }
}
var PluginErrorAdmin = new PluginErrorAdmin();
