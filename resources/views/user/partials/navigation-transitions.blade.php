<style>
  @view-transition {
    navigation: auto;
  }

  @keyframes page-fade-out {
    to { opacity: 0; }
  }

  @keyframes page-fade-in {
    from { opacity: 0; }
  }

  ::view-transition-old(root) {
    animation: page-fade-out 220ms ease both;
  }

  ::view-transition-new(root) {
    animation: page-fade-in 220ms ease both;
  }

  ::view-transition-group(site-header),
  ::view-transition-old(site-header),
  ::view-transition-new(site-header) {
    animation: none !important;
  }

</style>
