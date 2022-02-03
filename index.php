<?PHP
session_start();
require_once __DIR__ . '/componentes/cabecalho.php';
require_once __DIR__ . '/componentes/menu.php';
$senha_gerada = $_SESSION['senha_gerada'] ?? '';
$palavra_chave = $_SESSION['palavra_chave'] ?? '';
$quantidade_caracteres = $_SESSION['quantidade_caracteres'] ?? '64';
?>
<main class="container">
  <div class="row valign-wrapper center-align">
    <form action="controllers/gerar_senha.php" method="POST" class="col s12">

      <div class="row valign-wrapper">

        <div class=" col s3 l1">
          <a id="palavra-chave-btn" class="btn-floating btn-large <?= empty($senha_gerada) ? 'pulse' : 'red lighten-2 ' ?>"><i class="material-icons">lightbulb_outline</i></a>
        </div>
        <!-- Tap Target Structure -->
        <div class="tap-target light-blue lighten-2" data-target="palavra-chave-btn">
          <div class="tap-target-content Black-text">
            <h5><?= APP_NAME ?></h5>
            <p class="Black-text left-align">Esta aplicação é destinada para gerar senhas fortes, por meio de uma frase simples. <br>
              <small> Por favor insira a frase para ser lembrada, para gerar uma senha forte</small>
            </p>
          </div>
        </div>

        <div class="input-field col s9 l11">
          <!--<i class="material-icons prefix">lightbulb_outline</i>-->
          <input id="palavra-chave" type="text" class="validate" name="palavra-chave" value="<?= $palavra_chave ?>">
          <label for="palavra-chave">palavra-chave</label>
        </div>
        <div class="input-field col s3">
          <!--<i class="material-icons prefix">content_copy</i>-->
          <input id="quantidadeCaracteres" type="number" class="" name="quantidade_caracteres" value="<?= $quantidade_caracteres?>">
          <label for="quantidadeCaracteres"> Quantidade Caracteres</label>
        </div>
      </div>
      <button class="btn waves-effect waves-light green lighten-2" type="submit" name="action">Gerar Senha
        <i class="material-icons right">enhanced_encryption</i>
      </button>
      <button id="limpar" class="btn  waves-effect waves-light red" type="reset" name="action">Limpar
        <i class="material-icons right">delete</i>
      </button>
      <!--<p>
       <label>
         class="filled-in" checked="checked"
        <input type="checkbox" name="possui_caracteres_especiais"/>
        <span>caracteres especiais?</span>
       </label>
       </p>-->
    </form>
  </div>
  <?php
  if (!empty($senha_gerada)) {

  ?>
    <div class="row valign-wrapper center-align">
      <div class="col s12">
        <div class="row valign-wrapper">
          <div class="col s3 l1">
            <a class="btn-floating btn-large pulse clipboard-btn indigo lighten-1" data-clipboard-target="#senha_gerada"><i class="material-icons">content_copy</i></a>
          </div>
          <div class="input-field col s9 l11">
            <!--<i class="material-icons prefix">content_copy</i>-->
            <input id="senha_gerada" type="text" class="" readonly value="<?= $senha_gerada ?>">
            <label for="senha_gerada">Senha Gerada</label>
          </div>
        </div>
      </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    <script>
      new ClipboardJS('.clipboard-btn');
      const btn_copiar = document.querySelector('.clipboard-btn');
      const btn_copiar_pulsar = document.querySelector('.clipboard-btn.pulse');
      btn_copiar.addEventListener('click', function() {
        M.toast({
          html: 'Copiado para a Área de transferência!'
        }, 3000);
        btn_copiar_pulsar.classList.toggle('red');
        btn_copiar_pulsar.classList.toggle('lighten-2');
        btn_copiar_pulsar.classList.toggle('pulse');
      });
    </script>
  <?php
  }
  ?>
</main>
<?PHP
require_once __DIR__ . '/componentes/rodape.php';
?>
<script>
  $(document).ready(function() {
    $('.tap-target').tapTarget();
    $('#palavra-chave-btn').on('click', function() {
      $('.tap-target').tapTarget('open');
      $(this).removeClass('pulse');
    });

    $('#limpar').on('click',
      function() {
        document.location.href = 'controllers/limpar_sessao.php';
      }
    );
  });
</script>