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

    @foreach($categories as $category)
        <?php
        $category_tree[$category->id] = $category->parent_category_id;
        $category_names_tree[$category->id] = $category->title;
        ?>
    @endforeach

    <?php
    $parent_category_id = $cat->parent_category_id;
    $current_category_id = $cat->id;

    $_SESSION["count"] = 0;

    function printSelectTree($tree, $category_names_tree, $current_category_id, $parent_category_id = null)
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
                if ($parent_category_id == $node['name']) //select the current parent id
                {
                    echo "<option value='" . $node['name'] . "' selected='selected'>" . $spacing . $category_names_tree[$node['name']] . "</option>";
                } else if ($node['name'] != $current_category_id) //we do not want to select itself as a parent
                {
                    echo "<option value='" . $node['name'] . "'>" . $spacing . $category_names_tree[$node['name']] . "</option>";
                }

                printSelectTree($node['children'], $category_names_tree, $current_category_id, $parent_category_id);
            }
            $_SESSION["count"]--;
        }
    }
    ?>

    <form method="post" action="/categories/{{ $cat->id }}" id="category_form"
          name="category_form">
        {{ method_field('PATCH') }}
        @csrf

        <div class="paragraph">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input @error('title') is-invalid @enderror"
                       id="title" type="text" name="title" value="{{ $cat->title }}"/>
                <label class="mdl-textfield__label" for="title">{{ __('Title') }}</label>

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
                          name="description" id="description">{{ $cat->description }}</textarea>
                <label class="mdl-textfield__label" for="description">{{ __('Description') }}</label>

                @error('description')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="field">
            <div class="control">
                {{ __('Visibility of this Category') }}
                <div class="select is-info">
                    <select name="visibility" id="visibility">
                        <option @if ($cat->visibility === 'public') selected="selected"
                                @endif value="private">{{ __('Private') }}</option>
                        <option @if ($cat->visibility === 'public') selected="selected"
                                @endif value="public">{{ __('Public') }}</option>
                        <option @if ($cat->visibility === 'friends') selected="selected"
                                @endif value="friends">{{ __('Friends') }}</option>
                    </select>
                </div>
            </div>
        </div>

        @if ($is_admin)
            <div class="field">
                <div class="control">
                    {{ __('Category Status') }}
                    <div class="select is-info">
                        <select name="category_status" id="category_status">
                            <option @if ($cat->category_status === 'live') selected="selected"
                                    @endif value="live">{{ __('Live') }}</option>
                            <option @if ($cat->category_status === 'deleted') selected="selected"
                                    @endif value="deleted">{{ __('Deleted') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        @endif

        @if($count>0)
            <div class="field">
                <div class="control">
                    {{ __('Parent Category') }}
                    <div class="select is-info"><select name="parent_category_id" id="parent_category_id">
                            <option value="0">No Parent Category</option>
                            <?php
                            $result = parseTree($category_tree, null, 0);
                            printSelectTree($result, $category_names_tree, $current_category_id, $parent_category_id);
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        @else
            <input type="hidden" name="parent_category_id" id="parent_category_id" value="0"/>
        @endif
        <div class="buttons">
            <button type="submit"
                    class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                {{ __('Update') }}
            </button>
            @if($cat->category_status === 'deleted')
                <a href="/categories/{{ $cat->id }}/restore"
                   class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                    {{ __('Restore') }}</a>
            @else
                <a href="/categories/{{ $cat->id }}/delete"
                   class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent"
                   onclick="return confirm('Are you sure you want to delete this category?')">{{ __('Delete') }}</a>
            @endif
            <a href="{{ route('categories.index') }}"
               class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
                {{ __('Back to List') }}</a>
        </div>
    </form>

    <div class="errors">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endsection
