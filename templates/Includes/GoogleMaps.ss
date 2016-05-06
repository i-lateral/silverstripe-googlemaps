<% if $Maps %>
    <div class="google-maps">
        <% loop $Maps %>
            <div class="google-map <% if $Top.StaticMap %>google-map-static<% end_if %>">
                <% if $Title %><h2>$Title</h2><% end_if %>
                <% if $Top.StaticMap %>
                    <div class="google-map-content">
                        $Content
                    </div>
                    <div class="google-map-map">
                        <a href="$Link">
                            <img src="$ImgURL(600,300)" alt="$Address" />
                        </a>
                    </div>
                <% else %>
                    <div class="google-map-dynamic google-map-dynamic-{$ID}" style="width: 100%; height: 350px;">
                        <p>Map Loading...</p>
                    </div>
                <% end_if %>
            </div>
        <% end_loop %>
        
        <div class="clear google-maps-clear"></div>
    </div>
<% end_if %>
