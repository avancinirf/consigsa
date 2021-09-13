<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" v-for="title, key in titles" :key="key">{{title.label}}</th>
                <th v-if="actions && (actions.view || actions.edit || actions.remode)">Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="obj, key in filteredData" :key="key">
                <td v-for="value, keyValue in obj" :key="keyValue">
                    <span v-if="titles[keyValue].type == 'text'">{{value}}</span>
                    <!-- TODO: Criar formatação de campos do tipo data -->
                    <span v-if="titles[keyValue].type == 'date'">{{ value | convertDateToPT }}</span>
                    <!-- TODO: Testar caminho para imagem e colocar link para arquivo
                    <span v-if="titles[keyValue].type == 'img'"><img :src="'/storage/'+value" width="30" height="30"></span>
                    <span v-if="titles[keyValue].type == 'file'">...{{value}}</span>
                    -->
                </td>
                <td v-if="actions && (actions.view || actions.edit || actions.remode)">
                    <button v-if="actions.view" class="btn btn-outline-primary btn-sm" :data-toggle=actions.view.dataToggle :data-target=actions.view.dataTarget @click="setStore(obj)"><i class="bi bi-eye-fill"></i></button>
                    <button v-if="actions.edit" class="btn btn-outline-primary btn-sm" :data-toggle=actions.edit.dataToggle :data-target=actions.edit.dataTarget @click="setStore(obj)"><i class="bi bi-pencil-fill"></i></button>
                    <button v-if="actions.remove" class="btn btn-outline-danger btn-sm" :data-toggle=actions.remove.dataToggle :data-target=actions.remove.dataTarget @click="setStore(obj)"><i class="bi bi-trash-fill"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: ['data', 'titles', 'actions'],
        methods: {
            setStore(obj) {
                this.$store.state.transaction = { status: '', message: '', data: ''}
                this.$store.state.item = obj
            }
        },
        computed: {
            filteredData() {

                let fields = Object.keys(this.titles)
                let filteredData = []

                this.data.map((item, key) => {
                    let filteredItem = {}
                    fields.forEach(field => {
                        filteredItem[field] = item[field] || ''
                    })
                    filteredData.push(filteredItem)
                })
                return filteredData
            }
        }
    }
</script>
