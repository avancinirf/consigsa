<template>
    <div class="form-group">
        <label :for="id" class="form-label">{{title}}</label>
        <select class="form-control">
            <option selected value="" disabled>Selecione uma opção</option>
            <option v-for="client in clients" :value="client.id" :key="client.id">
                {{ client.name }}
            </option>
        </select>
        <small :id="helpId" class="form-text text-muted">{{helpText}}</small>

    </div>
</template>

<script>
    export default {
        props: ['id', 'title', 'helpId', 'helpText', 'options', 'selected']
    }
</script>
