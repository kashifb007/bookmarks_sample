<script>
    new ClipboardJS('.copy-button');

    function openInNewTab(url) {
        var win = window.open(url, '_blank');
        win.focus();
    }

    // bind change event to select
    function openPage(url) {
        if (url != 0) { // require a URL
            window.location = url; // redirect
        }
        return false;
    }

</script>
<div class="mdl-grid">
    @if($showSites === true)
        <div class="mdl-cell mdl-cell--4-col">
            @else
                <div class="mdl-cell mdl-cell--6-col center">
                    @endif
                    <div class="select field">
                        <div class="control">
                            <div class="ui fluid search selection dropdown">
                                <input type="hidden" name="category" id="category">
                                <i class="dropdown icon"></i>
                                <div class="default text">{{ __('Select Category') }}</div>
                                <div class="menu">
                                    <?php
                                    echo $treeData;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="mdc-icon-button material-icons mdc-card__action mdc-card__action--icon--unbounded"
                            onclick="openPage($('#category').val())">
                        <i class="material-icons mdc-icon-button__icon">pageview</i>
                    </button>
                </div>
                @if($showSites === true)
                    <div class="mdl-cell mdl-cell--4-col">
                        <div class="select field">
                            <div class="control">
                                <div class="ui fluid search selection dropdown">
                                    <input type="hidden" name="tags" id="tags">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">{{ __('Select Tag') }}</div>
                                    <div class="menu">
                                        @foreach($tags as $tag)
                                            <div class="item"
                                                 data-value="{{ url('tags/' . $tag->id . '/show') }}">{{ $tag->title }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="mdc-icon-button material-icons mdc-card__action mdc-card__action--icon--unbounded"
                                onclick="openPage($('#tags').val())">
                            <i class="material-icons mdc-icon-button__icon">pageview</i>
                        </button>
                    </div>
                    <div class="mdl-cell mdl-cell--4-col">
                        <div class="select field">
                            <div class="control">
                                <div class="ui fluid search selection dropdown">
                                    <input type="hidden" name="sites" id="sites">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">{{ __('Select Site') }}</div>
                                    <div class="menu">
                                        @foreach($sites as $site)
                                            <div class="item"
                                                 data-value="{{ url('sites/' . $site->id . '/show') }}">{{ $site->rootLink->url }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="mdc-icon-button material-icons mdc-card__action mdc-card__action--icon--unbounded"
                                onclick="openPage($('#sites').val())">
                            <i class="material-icons mdc-icon-button__icon">pageview</i>
                        </button>
                    </div>
                @endif
        </div>
</div>
