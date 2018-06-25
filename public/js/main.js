/*=== Filters ===*/
$('body').on('change', '.w_sidebar input', function () {
   var checked = $('.w_sidebar input:checked'),
       data = '';

   // запускаем цикл (отслеживаем состояние inputs)
   checked.each(function () {
      data += this.value + ',';
   });

   if (data) {
      // отправка ajax запроса
      $.ajax({
          url: location.href,
          data: {filter: data},
          type: 'GET',
          // вкл preloader перед отправкой и скрываем продукты
          beforeSend: function () {
              $('.preloader').fadeIn(300, function () {
                  $('.product-one').hide();
              });
          },
          // при положительном ответе сервера
          // выкл preloader и показываем продукты в соответствии
          // с выставленными фильтрами
          success: function (res) {
              $('.preloader').delay(500).fadeOut('slow', function () {
                  $('.product-one').html(res).fadeIn();
                  // формируем новый url добавляя фильтры
                  var url = location.search.replace(/filter(.+?)(&|$)/g, '');
                  var newURL = location.pathname + url + (location.search ? "&" : "?") + "filter=" + data;

                  newURL = newURL.replace('&&', '&');
                  newURL = newURL.replace('?&', '?');
                  history.pushState({}, '', newURL);
              });
          },
          error: function () {
              alert('Ошибка!');
          }
      });
   } else {
      // когда снят последний фильтр - перезапрашиваем страницу
      window.location = location.pathname;
   }

   //console.log(data);
});

/*=== End Filters ===*/

/*=== Search ===*/
var products = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        wildcard: '%QUERY',
        url: path + '/search/typeahead?query=%QUERY'
    }
});

products.initialize();

$("#typeahead").typeahead({
    // hint: false,
    highlight: true
},{
    name: 'products',
    display: 'title',
    limit: 10,
    source: products
});

$('#typeahead').bind('typeahead:select', function(ev, suggestion) {
    // console.log(suggestion);
    window.location = path + '/search/?s=' + encodeURIComponent(suggestion.title);
});

/*=== End Search ===*/

/*=== Cart ===*/
$('body').on('click', '.add-to-cart-link', function(e){
    e.preventDefault();
    var id = $(this).data('id'),
        qty = $('.quantity input').val() ? $('.quantity input').val() : 1,
        mod = $('.available select').val();
    $.ajax({
        url: '/cart/add',
        data: {id: id, qty: qty, mod: mod},
        type: 'GET',
        success: function(res){
            showCart(res);
        },
        error: function(){
            alert('Ошибка! Попробуйте позже');
        }
    });
});

$('#cart .modal-body').on('click','.del-item', function () {
    var id = $(this).data('id');
    $.ajax({
        url: '/cart/delete',
        data: {id: id},
        type: 'GET',
        success: function (res) {
            showCart(res);
        },
        error: function () {
            alert('Ошибка!');
        }
    });
});


function showCart(cart){
    if($.trim(cart) == '<h3>Корзина пуста</h3>'){
        $('#cart .modal-footer a, #cart .modal-footer .btn-danger').css('display', 'none');
    }else{
        $('#cart .modal-footer a, #cart .modal-footer .btn-danger').css('display', 'inline-block');
    }
    $('#cart .modal-body').html(cart);
    $('#cart').modal();

    if($('.cart-sum').text()){
        $('.simpleCart_total').html($('#cart .cart-sum').text());
    }else{
        $('.simpleCart_total').text('Корзина пуста');
    }
}

function clearCart() {
    $.ajax({
        url: '/cart/clear',
        type: 'GET',
        success: function(res){
            showCart(res);
        },
        error: function(){
            alert('Ошибка! Попробуйте позже');
        }
    });
}

function getCart() {
    $.ajax({
        url: '/cart/show',
        type: 'GET',
        success: function(res){
            showCart(res);
        },
        error: function(){
            alert('Ошибка! Попробуйте позже');
        }
    });
}
/*=== Cart ===*/

$('#currency').change(function () {
    window.location = 'currency/change?curr=' + $(this).val();
});

$('.available select').on('change', function(){
    var modId = $(this).val(),
        color = $(this).find('option').filter(':selected').data('title'),
        price = $(this).find('option').filter(':selected').data('price'),
        basePrice = $('#base-price').data('base');
    if (price) {
        $('#base-price').text(symbolLeft + price + symbolRight);
    } else {
        $('#base-price').text(symbolLeft + basePrice + symbolRight);
    }

    //console.log(modId, color, price);
});