<?PHP
session_start();
require_once __DIR__.'/componentes/cabecalho.php';
require_once __DIR__.'/componentes/menu.php';
$senha_gerada = $_SESSION['senha_gerada'] ?? '';
$palavra_chave = $_SESSION['palavra_chave'] ?? '';
?>
<div class="row">
    <form action="gerar_senha.php" method="POST" class="col s12">
      <div class="row">
        <div class="input-field col s12">
        <i class="material-icons prefix">send</i>
          <input id="palavra-chave" type="text" class="validate" name="palavra-chave" value="<?=$palavra_chave?>">
          <label for="palavra-chave">palavra-chave</label>
        </div>
      </div>
      <button class="btn waves-effect waves-light" type="submit" name="action">Gerar Senha
    <i class="material-icons right">enhanced_encryption</i>
  </button>
    </form>

  <div class="row">
    <form class="col s12">
      <div class="row">
        <div class="input-field col s12">
          <i class="material-icons prefix">https</i>
          <input id="icon_prefix" type="text" class="validate" readonly value="<?=$senha_gerada?>"> 
          <label for="icon_prefix">Senha Gerada</label>
        </div>
      </div>
  </div> 
  </div>
<?PHP
require_once __DIR__.'/componentes/rodape.php';
?>