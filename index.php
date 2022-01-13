<?PHP
session_start();
require_once __DIR__.'/componentes/cabecalho.php';
require_once __DIR__.'/componentes/menu.php';
$senha_gerada = $_SESSION['senha_gerada'] ?? '';
$palavra_chave = $_SESSION['palavra_chave'] ?? '';
?>
<main class="container">
<div class="row valign-wrapper center-align">
    <form action="gerar_senha.php" method="POST" class="col s12">
      <div class="row valign-wrapper">
        <div class="input-field col s12">
        <i class="material-icons prefix">lightbulb_outline</i>
          <input id="palavra-chave" type="text" class="validate" name="palavra-chave" value="<?=$palavra_chave?>">
          <label for="palavra-chave">palavra-chave</label>
        </div>
      </div>
      <button class="btn waves-effect waves-light" type="submit" name="action">Gerar Senha
    <i class="material-icons right">enhanced_encryption</i>
  </button>
    </form>
  </div>
  <div class="row valign-wrapper">
      
        <div class="input-field col s12">
          <i class="material-icons prefix">https</i>
          <input id="icon_prefix" type="text" class="validate" readonly value="<?=$senha_gerada?>"> 
          <label for="icon_prefix">Senha Gerada</label>
        </div>
  </div> 
  </main>
<?PHP
require_once __DIR__.'/componentes/rodape.php';
?>