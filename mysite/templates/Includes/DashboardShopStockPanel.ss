<div class="dashboard-shop-stock">
  <ul>
    <% loop Products %>
    <li><a href="$Link"><span class="">$Title</span><span style="float:right">Stock: $Stock</span></a></li>
    <% end_loop %>
  </ul>
</div>