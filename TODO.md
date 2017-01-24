# Required

1. Finalize `resource` document :
    * Implement `relationships`
1. Finalize `AbstractDocument` :
    * Implement `errors`
    * Implement `jsonapi`
    * Implement `included`
1. Implement `collectionDocument`
1. Finalize `error` :
    * Implement `id`
    * Implement `links`
    * Implement `code`
    * Implement `source`
    * Implement `meta`

# Features
1. If `meta` is empty, return directly the `href` property as a string.
1. Remove or replace non relevant constant `ALLOWED_KEYS`
