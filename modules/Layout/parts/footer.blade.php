@includeIf('Layout::parts.browser')
<script>
    @auth
            window.Permissions = {!! json_encode(Auth::user()->allPermissions, true) !!};
    @else
            window.Permissions = [];
    @endauth
    
    var contractType = '{!! json_encode(CONTRACT_TYPE) !!}';
    var contentType = '{!! json_encode(CONTENT_TYPE) !!}';
    var invoiceType = '{!! json_encode(INVOICE_TYPES) !!}';
    let cmsHatt = {
        contract_type: contractType,
        content_type: contentType,
        invoice_type: invoiceType,
        i18n: {
            warning: "{{__("Warning")}}",
            success: "{{__("Success")}}",
            confirm_delete: "{{__("Do you want to delete?")}}",
            confirm_recovery: "{{__("Do you want to restore?")}}",
            confirm: "{{__("Confirm")}}",
            cancel: "{{__("Cancel")}}",
        },
        route: {
            contract: {
                search: '{{route("contract.search")}}',
                store: '{{route("contract.store",['id'=>null])}}',
                edit: '{{route("contract.edit",['id'=>null])}}',
                delete: '{{route("contract.delete",['id'=>null])}}',
                select2: '{{route("contract.select2")}}',
                import: '{{route("contract.import")}}',
                export: '{{route("contract.export")}}',
            },
            content: {
                search: '{{route("content.search")}}',
                store: '{{route("content.store")}}',
                edit: '{{route("content.edit")}}',
                delete: '{{route("content.delete",['id'=>null])}}',
                import: '{{route("content.import")}}',
                export: '{{route("content.export")}}',
            },
            partner: {
                search: '{{route("partner.search")}}',
                store: '{{route("partner.store")}}',
                edit: '{{route("partner.edit")}}',
                delete: '{{route("partner.delete",['id'=>null])}}',
                import: '{{route("partner.import")}}',
                export: '{{route("partner.export")}}',
            },
            user: {
                search: '{{route("user.search")}}',
                store: '{{route("user.store")}}',
                edit: '{{route("user.edit")}}',
                delete: '{{route("user.delete",['id'=>null])}}',
                role:{
                    select2: '{{route("user.role.select2")}}',
                    search: '{{route("user.role.search")}}',
                    store: '{{route("user.role.store")}}',
                    edit: '{{route("user.role.edit")}}',
                },
                permission:{
                    all: '{{route("user.permission.all")}}',
                }
            },
            invoice: {
                search: '{{route("invoice.search")}}',
                store: '{{route("invoice.store")}}',
                edit: '{{route("invoice.edit")}}',
                delete: '{{route("invoice.delete",['id'=>null])}}',
                export: '{{route("invoice.export")}}',
                import: '{{route("invoice.import")}}',
            },
            media: {
                getLists: '{{route('media.getLists')}}',
                getFilesInfo: '{{route('media.getFilesInfo')}}',
                removeFiles: '{{route('media.removeFiles')}}',
                uploads: '{{route('media.uploads')}}',
                preview: '{{route('media.preview',['id'=>null,'size'=>null])}}',
            },

        },
    };
</script>
<script src="{{ asset('js/manifest.js') }}" defer></script>
<script src="{{ asset('js/vendor.js?version='.time()) }}" defer></script>
<script src="{{ asset('js/app.js?version='.time()) }}" defer></script>
@yield('script-footer')
</body>
</html>
