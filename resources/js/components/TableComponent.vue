<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" v-for="title, key in titles" :key="key">{{title.label}}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="obj, key in filteredData" :key="key">
                <td v-for="value, keyValue in obj" :key="keyValue">
                    <span v-if="titles[keyValue].type == 'text'">{{value}}</span>
                    <!-- TODO: Criar formatação de campos do tipo data -->
                    <span v-if="titles[keyValue].type == 'date'">{{value}}</span>
                    <!-- TODO: Testar caminho para imagem e colocar link para arquivo
                    <span v-if="titles[keyValue].type == 'img'"><img :src="'/storage/'+value" width="30" height="30"></span>
                    <span v-if="titles[keyValue].type == 'file'">...{{value}}</span>
                    -->
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: ['data', 'titles'],
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
