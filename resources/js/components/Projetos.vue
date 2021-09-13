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
                            :actions="{
                                view: { dataToggle: 'modal', dataTarget: '#viewProjectModal' },
                                edit: { dataToggle: 'modal', dataTarget: '#editProjectModal' },
                                remove: { dataToggle: 'modal', dataTarget: '#removeProjectModal' },
                            }"
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


        <!-- Start ADD Project MODAL -->
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
                    <input-container-component id="addDescription" title="Descrição" help-id="descriptionHelp">
                        <textarea class="form-control" id="addDescription" rows="3" aria-describedby="descriptionHelp" placeholder="Descrição do projeto" v-model="projectDescription">"</textarea>
                    </input-container-component>
                </div>
            </template>

            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success" @click="save()">Salvar</button>
            </template>

        </modal-component>
        <!-- End ADD Project MODAL -->


        <!-- Start VIEW Project MODAL -->
        <modal-component id="viewProjectModal" title="Visualizar projeto">

            <template v-slot:alerts>
            </template>

            <template v-slot:body>
                <input-container-component title="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component title="Nome">
                    <input type="text" class="form-control" :value="$store.state.item.name" disabled>
                </input-container-component>
                <input-container-component title="Iniciado em">
                    <input type="text" class="form-control" :value="$store.state.item.started_at" disabled>
                </input-container-component>
                <input-container-component title="Finalizado em">
                    <input type="text" class="form-control" :value="$store.state.item.finished_at" disabled>
                </input-container-component>
                <input-container-component title="Descrição">
                    <textarea class="form-control" id="addDescription" rows="3" :value="$store.state.item.description" disabled></textarea>
                </input-container-component>

                <!-- EXEMPLO DE CAMPO IMAGEM OU ARQUIVO -->
                <!--
                    O V-IF remove um erro ao criar o modal, pois no momento da criação,
                    o campo com o nome da imagem ainda não existe. $store.state.item.imagem = undefined.
                    Assim, apenas exibe o campo se houver uma imagem de fato. Isso serve para o link de arquivos.
                -->
                <!--
                <input-container-component title="Finalizado em">
                    <img :src="'storage/'+$store.state.item.imagem" v-if="$store.state.item.imagem">
                </input-container-component>
                -->
            </template>

            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </template>

        </modal-component>
        <!-- End VIEW Project MODAL -->


        <!-- Start EDIT Project MODAL -->
        <modal-component id="editProjectModal" title="Atualizar projeto">

            <template v-slot:alerts>
                <alert-component type="success" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'success'" title="Projeto editado com sucesso!"></alert-component>
                <alert-component type="danger" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'" title="Erro ao editar projeto."></alert-component>
            </template>

            <template v-slot:body>
                <input-container-component title="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component title="Nome">
                    <input type="text" class="form-control" v-model="$store.state.item.name">
                </input-container-component>
                <input-container-component title="Iniciado em">
                    <input type="text" class="form-control" v-model="$store.state.item.started_at">
                </input-container-component>
                <input-container-component title="Finalizado em">
                    <input type="text" class="form-control" v-model="$store.state.item.finished_at">
                </input-container-component>
                <input-container-component title="Descrição">
                    <textarea class="form-control" id="addDescription" rows="3" v-model="$store.state.item.description"></textarea>
                </input-container-component>
            </template>

            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success" @click="update()">Atualizar</button>
            </template>

        </modal-component>
        <!-- End EDIT Project MODAL -->


        <!-- Start REMOVE Project MODAL -->
        <modal-component id="removeProjectModal" title="Remover projeto">

            <template v-slot:alerts>
                <alert-component type="success" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'success'" title="Projeto removido com sucesso!"></alert-component>
                <alert-component type="danger" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'" title="Erro ao remover projeto."></alert-component>
            </template>

            <template v-slot:body v-if="$store.state.transaction.status != 'success'">
                <input-container-component title="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component title="Nome">
                    <input type="text" class="form-control" :value="$store.state.item.name" disabled>
                </input-container-component>
            </template>

            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remove" v-if="$store.state.transaction.status != 'success'">Remover</button>
            </template>

        </modal-component>
        <!-- End REMOVE Project MODAL -->


    </div>
</template>

<script>
import AlertComponent from './AlertComponent.vue'
import InputContainerComponent from './InputContainerComponent.vue'
import PaginateComponent from './PaginateComponent.vue'
    export default {
  components: { AlertComponent, PaginateComponent, InputContainerComponent },
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
            },
            update() {
                let formData = new FormData()
                formData.append('_method', 'patch')
                formData.append('name', this.$store.state.item.name)
                formData.append('description', this.$store.state.item.description)
                if (!this.$store.state.item.started_at.length) this.$store.state.item.started_at = ''
                if (!this.$store.state.item.finished_at.length) this.$store.state.item.finished_at = ''
                formData.append('started_at', this.$store.state.item.started_at)
                formData.append('finished_at', this.$store.state.item.finished_at)

                let url = `${this.projectUrl}/${this.$store.state.item.id}`

                let config = {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.post(url, formData, config)
                    .then(response => {
                        // TODO : Caso exista um campo para upload de arquivo, deve ser limpo o campo pelo id
                        // NomeDoCampoDeUploadDeArquivos.value = ''
                        // colocar um IF no formData.append para o campo caso ele não existe, pois só iremos informar o valor dele se o arquivo for submetido
                        this.$store.state.transaction.status = 'success'
                        this.$store.state.transaction.message = 'Projeto atualizado com sucesso.'
                        this.$store.state.transaction.data = ''
                        this.getList()
                    })
                    .catch(errors => {
                        this.$store.state.transaction.status = 'error'
                        this.$store.state.transaction.message = errors.response.data.message
                        this.$store.state.transaction.data = errors.response.data.errors
                    })
            },
            remove() {
                let confirmRemove = confirm('Tem certeza que deseja remover o registro?')
                if (!confirmRemove) return

                let url = `${this.projectUrl}/${this.$store.state.item.id}`
                let formData = new FormData();
                formData.append('_method', 'delete')

                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.post(url, formData, config)
                    .then(response => {
                        this.$store.state.transaction.status = 'success'
                        this.$store.state.transaction.message = response.data.message
                        this.getList()
                    })
                    .catch(errors => {
                        this.$store.state.transaction.status = 'error'
                        this.$store.state.transaction.message = errors.response.data.error
                    })
            }
        }
    }
</script>
