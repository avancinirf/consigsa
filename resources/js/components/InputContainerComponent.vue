<template>
    <div class="form-group">
        <label :for="id" class="form-label">{{title}}</label>
        <slot></slot>
        <div :id="helpId" class="form-text text-muted">{{helpText}}</div>
    </div>
</template>

<script>
    export default {
        props: ['id', 'title', 'helpId', 'helpText']
    }
</script>
