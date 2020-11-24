@extends('layouts.mdc')

@section('title', 'Welcome to My Bookmarks')
@section('meta_desc', 'Welcome to My Bookmarks')

@section('heading', 'My Bookmarks Home')

@section('content')

    <?php
    function parseTree($tree, $root = null)
    {
        $return = array();
        # Traverse the tree and search for direct children of the root
        foreach ($tree as $child => $parent) {
            # A direct child is found
            if ($parent == $root) {
                # Remove item from tree (we don't need to traverse this again)
                unset($tree[$child]);
                # Append the child into result array and parse its children
                $return[] = array(
                    'name' => $child,
                    'children' => parseTree($tree, $child)
                );
            }
        }
        return empty($return) ? null : $return;
    }

    $category_tree = array();
    $category_names_tree = array();
    ?>

    @foreach($models as $category)
        <?php
        $category_tree[$category->id] = $category->parent_category_id;
        $category_names_tree[$category->id] = $category->title;
        ?>
    @endforeach

    <?php
    $_SESSION["count"] = 0;

    function printSelectTree($tree, $category_names_tree)
    {
        if (!is_null($tree) && count($tree) > 0) {
            $_SESSION["count"]++;
            foreach ($tree as $node) {
                $spacing = '';
                for ($x = 0; $x < $_SESSION["count"]; $x++) {
                    $thespace = $_SESSION["count"] == 1 ? "&#8618;&#160" : "&#160&#160&#160";
                    $spacing .= $thespace;
                    //echo $spacing;
                }
                echo "<option value='" . $node['name'] . "'>" . $spacing . $category_names_tree[$node['name']] . "</option>";
                printSelectTree($node['children'], $category_names_tree);
            }
            $_SESSION["count"]--;
        }
    }
    ?>
    <form method="post" action="{{ route('categories.store') }}" id="category_form" name="category_form">
        @csrf

        <div class="paragraph">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input @error('title') is-invalid @enderror"
                       id="title" type="text" name="title" value="{{ old('title') }}" required />
                <label class="mdl-textfield__label" for="title">{{ __('Title') }}</label>
                <span class="mdl-textfield__error">Please enter a Title</span>

                @error('title')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="paragraph">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <textarea class="mdl-textfield__input" type="text" rows="3"
                          name="description" id="description">{{ old('description') }}</textarea>
                <label class="mdl-textfield__label" for="description">{{ __('Description') }}</label>

                @error('description')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        @if($count>0)
            <div class="field">
                <div class="control">
                    {{ __('Parent Category') }}

                    <div class="select is-info">
                        <select name="parent_category_id" id="parent_category_id">
                            <option value="0">No Parent Category</option>
                            <?php
                            $result = parseTree($category_tree, null, 0);
                            printSelectTree($result, $category_names_tree);
                            ?>
                        </select>
                    </div>

                </div>
            </div>
        @else
            <input type="hidden" name="parent_category_id" id="parent_category_id" value="0"/>
        @endif

        <div class="field">
            <div class="control">
                {{ __('Visibility of this Category') }}
                <div class="select is-info">
                    <select name="visibility" id="visibility">
                        <option selected="selected" value="private">{{ __('Private') }}</option>
                        <option value="public">{{ __('Public') }}</option>
                        <option value="friends">{{ __('Friends') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit"
                class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
            {{ __('Create') }}
        </button>

    </form>

    <br/><br/>

    <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored"
            onclick="location.href='{{ route('categories.index') }}'">
        {{ __('Back to List') }}
    </button>

    <div class="errors">

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

    </div>

@endsection
