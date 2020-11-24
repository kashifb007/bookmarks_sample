<script>
    $(document)
        .ready(function() {

            $('.ui.dropdown').dropdown();
            $('.ui.buttons .dropdown.button').dropdown({
                action: 'combo'
            });
        })
    ;
</script>
