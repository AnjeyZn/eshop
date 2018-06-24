<!--start-breadcrumbs-->
<div class="breadcrumbs">
  <div class="container">
    <div class="breadcrumbs-main">
      <ol class="breadcrumb">
        <li><a href="<?= PATH ?>">Главная</a></li>
        <li>Регистрация</li>
      </ol>
    </div>
  </div>
</div>
<!--end-breadcrumbs-->
<!--prdt-starts-->
<div class="prdt">
  <div class="container">
    <div class="prdt-top">
      <div class="col-md-12">
        <div class="product-one signup">
          <div class="register-top heading">
            <h2>REGISTER</h2>
          </div>

          <div class="register-main">
            <div class="col-md-6 account-left">
              <form method="post" action="user/signup" id="signup" role="form" data-toggle="validator">
                <div class="form-group has-feedback">
                  <label for="login">Login</label>
                  <input type="text" name="login" class="form-control" id="login" value="<?=isset($_SESSION['form_data']['login']) ? $_SESSION['form_data']['login'] : '';?>" placeholder="Login" required>
                  <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                </div>
                <div class="form-group has-feedback">
                  <label for="pasword">Password</label>
                  <input type="password" name="password" class="form-control" id="pasword" placeholder="Password" data-error="Введите не менее 6-ти символов" data-minlength="6" required>
                  <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                  <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback">
                  <label for="name">Имя</label>
                  <input type="text" name="name" class="form-control" id="name" value="<?=isset($_SESSION['form_data']['name']) ? $_SESSION['form_data']['name'] : '';?>" placeholder="Имя" required>
                  <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                </div>
                <div class="form-group has-feedback">
                  <label for="email">Email</label>
                  <input type="email" name="email" class="form-control" id="email" value="<?=isset($_SESSION['form_data']['email']) ? $_SESSION['form_data']['email'] : '';?>" placeholder="Email" required>
                  <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                </div>
                <div class="form-group has-feedback">
                  <label for="address">Address</label>
                  <input type="text" name="address" class="form-control" id="address" value="<?=isset($_SESSION['form_data']['address']) ? $_SESSION['form_data']['address'] : '';?>" placeholder="Address" required>
                  <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                </div>
                <button type="submit" class="btn btn-default">Зарегистрировать</button>
              </form>
              <?php if (isset($_SESSION['form_data'])) unset($_SESSION['form_data']);?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--product-end-->