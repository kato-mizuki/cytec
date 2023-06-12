$(function() {
  $('#search-btn').click(function(event) {
   var name = $('#name').val();
   var company = $('#company').val();
   var minPrice = $('#minPrice').val();
   var maxPrice = $('#maxPrice').val();
   var minStock = $('#minStock').val();
   var maxStock = $('#maxStock').val();
    console.log(name);
    console.log(company);
    console.log(minPrice);
    console.log(maxPrice);
    console.log(minStock);
    console.log(maxStock);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
      type: "get",
      url: "search/",
      datatype: "json",
       data: {
         keyword: name,
         companyId: company,
         minPrice: minPrice,
         maxPrice: maxPrice,
         minStock: minStock,
         maxStock: maxStock
       }
    }) //通信成功時の処理
    .done(function(data) {
      console.log('成功');
      console.log(data.price);
      console.log(data.stock);
      console.log(Array.isArray(data.products));
      var $result = $('#search-result');
      $result.empty(); //結果を一度クリア
      // console.log(data.companies[1].name);
      $.each(data.products, function (index, product) {
        console.log(data.products);
        //http://localhost:8888/cytech/public/storage/tobi-4DJ6m_1V71o-unsplash.jpg
        //storage/tobi-4DJ6m_1V71o-unsplash.jpg
        var imagePath = homeUrl + '/' + data.products[index].image_path.substr(6);
        console.log(imagePath);
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
                            <button type="submit" id="delete-btn" class="btn btn-secondary" onclick='return confirm("削除しますか？")'>削除</button>
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