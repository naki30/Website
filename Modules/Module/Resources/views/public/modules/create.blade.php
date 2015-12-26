@extends('layouts.master')

@section('styles')
    {!! Theme::style('css/simplemde.min.css') !!}
    <style>
        .CodeMirror {
            height: 300px;
        }
    </style>
@stop
@section('content')
    <section id="main" class="container">
        <header>
            <h2>Submitting a module</h2>
        </header>
        <div class="row box row-box">
            <div class="2u">
                @include('profile::public.partials.sidebar')
            </div>
            <div class="10u content" id="app">
                <a href="{{ route('p.modules.index') }}" class="button small pull-right"><i class="fa fa-arrow-circle-o-left"></i> Back</a>
                {!! Form::open(['route' => 'p.modules.store']) !!}
                <div class="row uniform 50%">
                    <div class="9u 12u(mobilep) {{ $errors->has('packagist_uri') ? ' has-error' : '' }}">
                        <input type="text" name="packagist_uri" id="packagist_uri" placeholder="Packagist vendor/name" v-model="packagist_uri">
                        {!! $errors->first('packagist_uri', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="3u 12u(mobilep)">
                        <input type="submit" value="Load" class="fit" @click.prevent="fetchData">
                    </div>
                </div>
                <div class="row uniform 50%">
                    <div class="6u 12u(mobilep) {{ $errors->has('vendor') ? ' has-error' : '' }}">
                        <input type="text" name="vendor" id="vendor" placeholder="Vendor" v-model="vendor">
                        {!! $errors->first('vendor', '<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="6u 12u(mobilep) {{ $errors->has('name') ? ' has-error' : '' }}">
                        <input type="text" name="name" id="name" placeholder="Name" v-model="name">
                        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="row uniform 50%">
                    <div class="12u 12u(mobilep) {{ $errors->has('excerpt') ? ' has-error' : '' }}">
                        <input type="text" name="excerpt" id="excerpt" placeholder="Excerpt" v-model="excerpt">
                        {!! $errors->first('excerpt', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="row uniform 50%">
                    <div class="12u{{ $errors->has('category') ? ' has-error' : '' }}">
                        <div class="select-wrapper">
                            <select name="category" id="category" v-model="category">
                                <option value="">- Category -</option>
                                <?php foreach ($categories as $category): ?>
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                <?php endforeach; ?>
                            </select>
                            {!! $errors->first('category', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="row uniform 50%">
                    <div class="12u{{ $errors->has('description') ? ' has-error' : '' }}">
                        <label for="description">Description</label>
                        <textarea name="description" class="descriptionMde" id="description" placeholder="Module Description"></textarea>
                        {!! $errors->first('description', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="row uniform 50%">
                    <div class="12u{{ $errors->has('documentation') ? ' has-error' : '' }}">
                        <label for="documentation">Documentation</label>
                        <textarea name="documentation" class="documentationMde" id="documentation"></textarea>
                        {!! $errors->first('documentation', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="row uniform">
                    <div class="12u">
                        <ul class="actions">
                            <li><input type="submit" value="Go to next step"></li>
                        </ul>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.13/vue.js"></script>
    {!! Theme::script('js/vue-resource.min.js') !!}
    {!! Theme::script('js/simplemde.min.js') !!}
    <script>
        var routes = {
            modulePackagistData: '{{ route('module.packagist_data') }}'
        };
        var oldInput = {
            vendor: '{{ old('vendor') }}',
            name: '{{ old('name') }}',
            excerpt: '{{ old('excerpt') }}',
            category: '{{ old('category') }}',
            description: '{{ old('description') }}',
            documentation: '{{ old('documentation') }}'
        };
        $( document ).ready(function() {
            var descriptionMde = new SimpleMDE({
                element: $(".descriptionMde")[0],
                spellChecker: false
            });
            var documentationMde = new SimpleMDE({
                element: $(".documentationMde")[0],
                spellChecker: false
            });
            Vue.http.headers.common['X-CSRF-TOKEN'] = window.document.querySelector('meta#token').getAttribute('value');
            new Vue({
                el: '#app',
                data: {
                    packagist_uri: 'asgardcms/notification-module',
                    vendor: oldInput.vendor,
                    name: oldInput.name,
                    excerpt: oldInput.excerpt,
                    description: '',
                    documentation: '',
                    category: oldInput.category
                },
                methods: {
                    fetchData: function () {
                        this.$http.post(routes.modulePackagistData, {packagist_uri: this.packagist_uri}, function (data) {
                            this.vendor = data.vendor;
                            this.name = data.name;
                            this.excerpt = data.excerpt;
                            descriptionMde.value(data.description);
                        });
                    }
                },
                ready() {
                    descriptionMde.value(oldInput.description);
                    documentationMde.value(oldInput.documentation);
                }
            })
        });
    </script>
@stop