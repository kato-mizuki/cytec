$(function() {
  $('#search-form').submit(function(event) {
    event.preventDefault(); //フォームの通常の通信をキャンセル
    $.ajax({
      type: "get",
      URl: "/search",
      datatype: "json",
      data: {
        name: $('#name').val(),
        company: $('#company').val()
      }
    }) //通信成功時の処理
    .done((response) => {
      var $result = $('#search.result');
      $result.empty(); //結果を一度クリア
      $.each(response.products, function(index,product) {
        var html =`
                 <tr>
                   <td>${product.name}</td>
                   <td>${product.price}</td>
                   <td>${product.stock}</td>
                   <td>${product.company_name}</td>
                   <td><img src=${product.image_path}"></td>
                 </tr>
                 `;
                 $result.append(html);
      });
    })
    .fail((error) => {
      console.log('失敗');
    });
  });
});