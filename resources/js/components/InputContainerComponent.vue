<template>
    <div class="form-group">
        <label :for="id" class="form-label">{{title}}</label>
        <slot></slot>
        <small :id="helpId" class="form-text text-muted">{{helpText}}</small>
    </div>
</template>

<script>
    export default {
        props: ['id', 'title', 'helpId', 'helpText']
    }
</script>
