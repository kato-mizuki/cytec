$(function() {
  $('#search-btn').click(function(event) {
   var name = $('#name').val();
   var company = $('#company').val();
    console.log(name);
    console.log(company);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
      type: "get",
      url: "search/",
      datatype: "json",
       data: {
         keyword: name,
         companyId: company
       }
    }) //通信成功時の処理
    .done(function(data) {
      console.log('成功');
      console.log(Array.isArray(data.products));
      var $result = $('#search-result');
      $result.empty(); //結果を一度クリア
      // console.log(data.companies[1].name);
      $.each(data.products, function (index, product) {
        console.log(data.products);
        //var imagePath = "http://localhost:8888/storage/coffee.exmple.jpg";
        var imagePath = "/storage" + data.products[index].image_path.substr(6);
        var html = `
                     <tr>
                       <td>${product.id}</td>
                       <td><img src="${imagePath}" class="imgsize"></td>
                       <td>${product.name}</td>
                       <td>${product.price}</td>
                       <td>${product.stock}</td>
                       <td>${data.companies[product.company_id -1].name}</td>
                       <td><a href="{{ route('show', ${product.id}) }}"><button class="btn btn-success">詳細</button></a></td>
                       <td><a href="{{ route('edit', ${product.id}) }}"><button class="btn btn-primary">編集</button></a></td>
                       <td>
                         <form action="{{ route('destroy', ${product.id}) }}" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-secondary" onclick='return confirm("削除しますか？")'>削除</button>
                          </form>
                        </td>
                     </tr>
                   `;
        $result.append(html);
      }); 
    })
    .fail(function(data) {
      console.log('失敗');
    }); 
  });
});