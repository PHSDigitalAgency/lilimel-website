<div class="dashboard-recent-orders">
  <ul>
    <% loop Orders %>
    <li><a href="$Link">$ID - $Member.Name - $TotalPrice.Nice</a></li>
    <% end_loop %>
  </ul>
</div>