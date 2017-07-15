<div class="$InnerClass" role="listbox">
  <% loop $EnabledSlides %>
    $renderSlide($First, $Middle, $Last)
  <% end_loop %>
</div>
