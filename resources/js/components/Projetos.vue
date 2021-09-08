<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <!-- Start search card -->
                <card-component title="Gestão de projetos">

                    <template v-slot:header>
                        <button type="button" class="btn btn-success btn-sm float-right" data-toggle="modal" data-target="#addProjectModal"><i class="bi bi-plus-lg"></i></button>
                    </template>

                    <template v-slot:body>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component id="inputId" title="ID" help-id="idHelp" help-text="Informe o ID do projeto. (opcional)">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component id="inputName" title="Nome do projeto" help-id="nameHelp" help-text="Informe o nome do projeto. (opcional)">
                                    <input type="text" class="form-control" id="inputName" aria-describedby="nameHelp" placeholder="Nome do projeto">
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:footer>
                        <button type="submit" class="btn btn-primary btn-sm float-right">Pesquisar</button>
                    </template>

                </card-component>
                <!-- End search card -->

                <!-- Start list card -->
                <card-component title="Resultado da pesquisa">

                    <template v-slot:body>
                        <table-component></table-component>
                    </template>

                    <template v-slot:footer>
                        <!--<button type="button" class="btn btn-success btn-sm float-right" data-toggle="modal" data-target="#addProjectModal">Adicionar projeto</button>-->
                    </template>

                </card-component>
                <!-- End list card -->
            </div>
        </div>


        <!-- Start modal -->
        <modal-component id="addProjectModal" title="Adicionar projeto">

            <template v-slot:alerts>
                <alert-component type="success" :details="transactionDetails" v-if="transactionStatus == 'success'" title="Cadastro realizado com sucesso!"></alert-component>
                <alert-component type="danger" :details="transactionDetails" v-if="transactionStatus == 'error'" title="Erro ao cadastrar projeto."></alert-component>
            </template>

            <template v-slot:body>
                <div class="form-group">
                    <input-container-component id="addName" title="Nome do projeto" help-id="addNameHelp">
                        <input type="text" class="form-control" id="addName" aria-describedby="addNameHelp" placeholder="Nome do projeto" v-model="projectName">
                    </input-container-component>
                    {{ projectName }}
                    <input-container-component id="addDescription" title="Descrição" help-id="descriptionHelp">
                        <input type="text" class="form-control" id="addDescription" aria-describedby="descriptionHelp" placeholder="Descrição do projeto" v-model="projectDescription">
                    </input-container-component>
                    {{ projectDescription }}
                    {{ clientId }}
                </div>
            </template>

            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success" @click="save">Salvar</button>
            </template>

        </modal-component>
        <!-- End modal -->

    </div>
</template>

<script>
import AlertComponent from './AlertComponent.vue'
    export default {
  components: { AlertComponent },
        computed: {
            token() {
                let token = document.cookie.split(';').find(index => index.includes('token='))
                token = token.split('=')[1]
                token = `Bearer ${token}`
                return token
            }
        },
        data() {
            return {
                baseUrl: 'http://consigsa.test/api/v1/project',
                clientId: '1',
                projectName: '',
                projectDescription: '',
                transactionStatus: '',
                transactionDetails: {},
                projects: []
            }
        },
        mounted() {
            this.getList()
        },
        methods: {
            getList() {
                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.get(this.baseUrl, config)
                    .then(response => {
                        this.projects = response.data
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            },
            save() {
                let formData = new FormData();

                formData.append('client_id', this.clientId)
                formData.append('name', this.projectName)
                formData.append('description', this.projectDescription)

                let config = {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.post(this.baseUrl, formData, config)
                    .then(response => {
                        this.transactionStatus = 'success'
                        this.transactionDetails = {
                            message: `ID do registro: ${response.data.id}`
                        }
                    })
                    .catch(errors => {
                        this.transactionStatus = 'error'
                        this.transactionDetails = {
                            message: `errors.response.data.message`,
                            data: errors.response.data.errors
                        }
                    })
            }
        }
    }
</script>
