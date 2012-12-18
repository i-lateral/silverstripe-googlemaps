<div id="GoogleMapBasic">
    <% if StaticMap %>
        <a href="$Link">
            <img src="$GoogleMapBasicStaticMapSource(600,450)" alt="$Address.ATT" width="600" height="450" />
        </a>
        <div class="staticInfoWindowContent">$Content</div>
    <% else %>
        <p>Map Loading...</p>
    <% end_if %>
</div>
