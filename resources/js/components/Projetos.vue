<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <!-- Start search card -->
                <card-component title="Gestão de projetos">

                    <template v-slot:header>
                        <button type="button" class="btn btn-success btn-sm float-right" data-toggle="modal" data-target="#addProjectModal"><i class="bi bi-plus-lg"></i></button>
                    </template>

                    <template v-slot:body>
                        <div class="form-row">
                            <div class="col mb-3">
                                <div class="form-group">
                                    <label class="form-label">Selecione um cliente</label>
                                    <select class="form-control" aria-describedby="selectClientHelp" v-model="searchData.client_id">
                                        <option selected value="">Selecione um cliente</option>
                                        <option v-for="client in clients" :value="client.id" :key="client.id">
                                            {{ client.name }}
                                        </option>
                                    </select>
                                    <small id="selectClientHelp" class="form-text text-muted">
                                        Informe o cliente. (opcional)
                                    </small>
                                </div>
                            </div>

                            <div class="col mb-3">
                                <input-container-component id="inputId" title="ID" help-id="idHelp" help-text="Informe o ID do projeto. (opcional)">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID" v-model="searchData.id">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component id="inputName" title="Nome do projeto" help-id="nameHelp" help-text="Informe o nome do projeto. (opcional)">
                                    <input type="text" class="form-control" id="inputName" aria-describedby="nameHelp" placeholder="Nome do projeto" v-model="searchData.name">
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:footer>
                        <button type="submit" class="btn btn-primary btn-sm float-right" @click="search()">Pesquisar</button>
                    </template>

                </card-component>
                <!-- End search card -->

                <!-- Start list card -->
                <card-component title="Resultado da pesquisa">

                    <template v-slot:body>
                        <table-component
                            :data="projects.data"
                            :titles="{
                                id: {label: 'ID', type: 'text'},
                                name: {label: 'Nome', type: 'text'},
                                description: {label: 'Descrição', type: 'text'},
                                started_at: {label: 'Iniciado em', type: 'date'},
                                finished_at: {label: 'Finalizado em', type: 'date'},
                                client_id: {label: 'ID Cliente', type: 'text'},
                            }"
                        ></table-component>
                    </template>

                    <template v-slot:footer>
                        <paginate-component>
                            <li v-for="page, key in projects.links" :key="key"
                                :class="page.active ? 'page-item active' : 'page-item'"
                                @click="paginate(page)"
                            >
                                <a class="page-link" v-html="page.label"></a>

                            </li>
                        </paginate-component>
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
                    <div class="form-group">
                        <label class="form-label">Selecione um cliente</label>
                        <select class="form-control" aria-label="Default select example" v-model="clientId">
                            <option selected value="" disabled>Selecione um cliente</option>
                            <option v-for="client in clients" :value="client.id" :key="client.id">
                                {{ client.name }}
                            </option>
                        </select>
                    </div>

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
import PaginateComponent from './PaginateComponent.vue'
    export default {
  components: { AlertComponent, PaginateComponent },
        computed: {
            token() {
                let token = document.cookie.split(';').find(index => index.includes('token='))
                token = token.split('=')[1]
                token = `Bearer ${token}`
                return token
            }
        },
        data() {
            const baseUrl = 'http://consigsa.test/api/v1/'
            return {
                baseUrl,
                clientUrl: `${baseUrl}client`,
                projectUrl: `${baseUrl}project`,
                paginationUrl: '',
                filterUrl: '',
                clientId: '',
                projectName: '',
                projectDescription: '',
                transactionStatus: '',
                transactionDetails: {},
                projects: { data: []},
                clients: [],
                selectedClientId: '',
                searchData: {
                    client_id: '',
                    id: '',
                    name: ''
                }
            }
        },
        mounted() {
            this.getList()
            this.getClients()
        },
        methods: {
            getClients() {
                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.get(this.clientUrl, config)
                    .then(response => {
                        this.clients = response.data
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            },
            getList() {
                let url = this.projectUrl
                let params = []
                if (this.paginationUrl) params.push(this.paginationUrl)
                if (this.filterUrl) params.push(this.filterUrl)

                if (this.paginationUrl || this.filterUrl) {
                    url += '?' + params.join('&')
                }

                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }
                axios.get(url, config)
                    .then(response => {
                        this.projects = response.data
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            },
            paginate(page) {
                if (!page.url) return
                this.paginationUrl = page.url.split('?')[1]
                this.getList()
            },
            search() {
                this.paginationUrl = 'page=1'
                if (!this.searchData.client_id && !this.searchData.id && !this.searchData.name) {
                    this.filterUrl = ''
                    this.getList()
                    return
                }

                let filters = []

                if (this.searchData.client_id) filters.push(`client_id:=:${this.searchData.client_id}`)
                if (this.searchData.id) filters.push(`id:=:${this.searchData.id}`)
                if (this.searchData.name) filters.push(`name:like:%${this.searchData.name}%`)

                this.filterUrl = `filters=${filters.join(';')}`
                this.getList()
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

                axios.post(this.projectUrl, formData, config)
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
