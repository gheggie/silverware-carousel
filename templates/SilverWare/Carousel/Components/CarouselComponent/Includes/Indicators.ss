<% if $IndicatorsShown %>
  <ol class="$IndicatorsClass">
    <% loop $EnabledSlides %>
      <li data-target="$Up.WrapperCSSID" data-slide-to="$Pos(0)" class="$Up.getIndicatorClass($First)"></li>
    <% end_loop %>
  </ol>
<% end_if %>
