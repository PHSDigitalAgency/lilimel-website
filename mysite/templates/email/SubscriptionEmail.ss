<html>
	<head>
		<style type="text/css">
				div.data span {
					width: 50%;
				}
			
				div.data span.left {
					text-align: right;
					font-weight: bold;
				}
				
				div.data a {
					overflow: visible;
				}
		</style>
	</head>
	<body>
		<h1>$Subject</h1>
		<p>Cher $FirstName,</p>
		<p>Merci de votre inscription à notre newsletter.</p>
		
		 <% if MemberInfoSection %>Vos informations&nbsp;:<br />
			<ul>
				<% loop MemberInfoSection %>
				<li><% if Title %>$Title<% else %>$Name<% end_if %>: $EmailalbeValue</li>
				<% end_loop %>
			</ul>
		<% end_if %>
		
		<% if Newsletters %>
			<p>Vous vous êtes inscrit à notre newsletter.</p>
		<% end_if %>
		
		<p>Pour vous désinscrire, veuillez cliquer <a href="$UnsubscribeLink">ici</a></p>

	</body>
</html>