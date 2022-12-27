<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <!--  Inicio do card de busca -->
        <card-component titulo="Busca de marcas">
          <template v-slot:conteudo>
            <div class="row">
              <div class="col-sm-6">
                <input-container-component
                  titulo="ID"
                  id="inputId"
                  id-help="idHelp"
                  texto-ajuda="Opcional. Informe o id da marca"
                >
                  <input
                    type="number"
                    class="form-control"
                    id="inputId"
                    aria-describedby="idHelp"
                    placeholder="Id"
                    min="0"
                    v-model="busca.id"
                  />
                </input-container-component>
              </div>
              <div class="col-sm-6">
                <input-container-component
                  titulo="Nome"
                  id="inputName"
                  id-help="idHelp"
                  texto-ajuda="Opcional. Informe o nome da marca"
                >
                  <input
                    type="text"
                    class="form-control"
                    id="inputNome"
                    aria-describedby="idNome"
                    placeholder="Nome da marca"
                    v-model="busca.nome"
                  />
                </input-container-component>
              </div>
            </div>
          </template>
          <template v-slot:rodape>
            <button
              type="submit"
              class="btn btn-primary btn-sm me-md-2"
              @click="pesquisar()"
            >
              Pesquisar
            </button>
          </template>
        </card-component>
        <!--  Fim do card de busca -->

        <!--  Inicio do card de listagem de marcas-->

        <card-component titulo="Relação de marcas">
          <template v-slot:conteudo>
            <table-component
              :data="marcas.data"
              :titulos="['ID', 'Nome', 'Imagem']"
              :visualizar="{
                visivel: true,
                dataToggle: 'modal',
                dataTarget: '#modalMarcaVisualizar',
              }"
              :atualizar="{
                visivel: true,
                dataToggle: 'modal',
                dataTarget: '#modalMarcaAtualizar',
              }"
              :deletar="{
                visivel: true,
                dataToggle: 'modal',
                dataTarget: '#modalMarcaRemover',
              }"
            ></table-component>
          </template>
          <template v-slot:rodape>
            <paginate-component>
              <li
                v-for="(l, key) in marcas.links"
                :key="key"
                :class="l.active ? 'page-item active' : 'page-item'"
                @click="paginacao(l)"
              >
                <a class="page-link" v-html="l.label"></a>
              </li>
            </paginate-component>
            <button
              type="submit"
              class="btn btn-primary btn-sm me-md-2"
              data-bs-toggle="modal"
              data-bs-target="#modalMarca"
            >
              Adicionar
            </button>
          </template>
        </card-component>

        <!--  Fim do card de listagem de marcas -->
      </div>
    </div>
    <!-- Inicio do modal de cadastro de marca -->
    <modal-component id="modalMarca" titulo="Cadastro de marca">
      <template v-slot:alerta>
        <alert-component
          tipo="success"
          :responseApi="responseApi"
          titulo="Marca cadastrada com sucesso"
          v-if="status == 'adicionado'"
        ></alert-component>
        <alert-component
          tipo="danger"
          :responseApi="responseApi"
          titulo="Erro ao cadastrar uma marca"
          v-if="status == 'erro'"
        ></alert-component>
      </template>
      <template v-slot:conteudo>
        <div class="form-group">
          <input-container-component
            titulo="Nome"
            id="novoNome"
            id-help="novoNomeHelp"
            texto-ajuda="Informe o nome da marca"
          >
            <input
              type="text"
              class="form-control"
              id="novoNome"
              aria-describedby="novoNomeHelp"
              placeholder="Nome da marca"
              v-model="nomeMarca"
            />
          </input-container-component>
        </div>

        <div class="form-group">
          <input-container-component
            titulo="Imagem"
            id="novoImagem"
            id-help="novoImagemHelp"
            texto-ajuda="Selecione uma imagem no formato PNG"
          >
            <input
              type="file"
              class="form-control"
              id="novoImagem"
              aria-describedby="novoImagemHelp"
              placeholder="Selecione uma imagem"
              @change="carregarImagem($event)"
            />
          </input-container-component>
        </div>
      </template>
      <template v-slot:rodape>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>
        <button type="button" class="btn btn-primary" @click="salvar()">
          Salvar
        </button>
      </template>
    </modal-component>
    <!-- Fim do modal de cadastro de marca -->

    <!-- Inicio do modal de visualização de marca -->

    <modal-component id="modalMarcaVisualizar" titulo="Visualizar marca">
      <template v-slot:alerta></template>
      <template v-slot:conteudo>
        <input-container-component titulo="ID">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.id"
            disabled
          />
        </input-container-component>
        <input-container-component titulo="Nome da marca">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.nome"
            disabled
          />
        </input-container-component>
        <input-container-component titulo="Imagem da marca">
          <img
            :src="'/storage/' + $store.state.item.imagem"
            :alt="$store.state.item.nome"
            v-if="$store.state.item.imagem"
          />
        </input-container-component>
      </template>
      <template v-slot:rodape>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>
      </template>
    </modal-component>

    <!-- Fim do modal de visualização de marca -->

    <!-- Inicio do modal de remoção de marca -->

    <modal-component id="modalMarcaRemover" titulo="Remover marca">
      <template v-slot:alerta>
        <alert-component
          :tipo="$store.state.transacao.status"
          :titulo="$store.state.transacao.mensagem"
          :responseApi="{ data: '{}' }"
          v-if="$store.state.transacao.status == 'success'"
        ></alert-component>
        <alert-component
          :tipo="$store.state.transacao.status"
          :titulo="$store.state.transacao.mensagem"
          :responseApi="{ data: '{}' }"
          v-if="$store.state.transacao.status == 'danger'"
        ></alert-component>
      </template>
      <template v-slot:conteudo>
        <input-container-component titulo="ID">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.id"
            disabled
          />
        </input-container-component>
        <input-container-component titulo="Nome da marca">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.nome"
            disabled
          />
        </input-container-component>
      </template>
      <template v-slot:rodape>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>
        <button
          type="button"
          class="btn btn-danger"
          @click="remover()"
          v-if="$store.state.transacao.status != 'success'"
        >
          Excluir
        </button>
      </template>
    </modal-component>

    <!-- Fim do modal de remoção de marca -->

        <!-- Inicio do modal de cadastro de marca -->
    <modal-component id="modalMarcaAtualizar" titulo="Atualização de marca">
      <template v-slot:alerta>
        <alert-component v-if="$store.state.transacao.status == 'success'" :tipo="$store.state.transacao.status" :titulo="$store.state.transacao.mensagem" :responseApi="responseApi"></alert-component>
        <alert-component v-if="$store.state.transacao.status == 'danger'" :tipo="$store.state.transacao.status" :titulo="$store.state.transacao.mensagem" :responseApi="responseApi"></alert-component>
      </template>
      <template v-slot:conteudo>
        <div class="form-group">
          <input-container-component
            titulo="Nome"
            id="atualizaNome"
            id-help="atualizaNomeHelp"
            texto-ajuda="Informe o nome da marca"
          >
            <input
              type="text"
              class="form-control"
              id="atualizaNome"
              aria-describedby="atualizaNomeHelp"
              placeholder="Nome da marca"
              v-model="$store.state.item.nome"
            />
          </input-container-component>
        </div>

        <div class="form-group">
          <input-container-component
            titulo="Imagem"
            id="atualizaImagem"
            id-help="atualizaImagemHelp"
            texto-ajuda="Selecione uma imagem no formato PNG"
          >
            <input
              type="file"
              class="form-control"
              id="atualizaImagem"
              aria-describedby="atualizaImagemHelp"
              placeholder="Selecione uma imagem"
              @change="carregarImagem($event)"
            />
          </input-container-component>
        </div>
      </template>
      <template v-slot:rodape>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>
        <button type="button" class="btn btn-primary" @click="atualizar()">
          Atualizar
        </button>
      </template>
    </modal-component>
    <!-- Fim do modal de cadastro de marca -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      urlBase: "http://localhost:8000/api/v1/marca",
      urlPaginacao: "",
      urlBusca: "",
      nomeMarca: "",
      arquivoImagem: [],
      status: "",
      responseApi: [],
      marcas: [],
      busca: { id: "", nome: "" },
    };
  },

  methods: {
    atualizar() {
        this.$store.state.transacao.data.id = ''
        this.$store.state.transacao.data.errors = {}

        let url = this.urlBase + '/' + this.$store.state.item.id

        let formData = new FormData()
        formData.append('_method', 'patch')
        formData.append('nome', this.$store.state.item.nome)
        if(this.arquivoImagem[0]) {
            formData.append('imagem', this.arquivoImagem[0])
        }

        let config = {
            headers: {
                'Authorization': this.token,
                'Content-Type': 'multipart/form-data',
                'Accept' : 'application/json'
            }
        }

        axios.post(url, formData, config)
        .then(response => {
            // console.log('atualizado com sucesso', response.data.msg)
            this.$store.state.transacao.status = 'success'
            this.$store.state.transacao.mensagem = response.data.msg
            this.$store.state.transacao.data.id = this.$store.state.item.id
            this.responseApi = this.$store.state.transacao
            atualizaImagem.value = ''
            this.carregarLista();
        })
        .catch(errors => {
            this.$store.state.transacao.status = 'danger'
            this.$store.state.transacao.mensagem = 'Erro ao atualizar a marca'
            this.$store.state.transacao.data.id = this.$store.state.item.id
            this.$store.state.transacao.data.errors = errors.response.data.errors
            atualizaImagem.value = ''
            this.responseApi = this.$store.state.transacao
        })
    },
    remover() {
      let confirmacao = confirm(
        "Tem certeza que deseja remover esse registro?"
      );
      if (!confirmacao) return false;
      let url = this.urlBase + "/" + this.$store.state.item.id;
      let formData = new FormData();
      formData.append("_method", "delete");

      let config = {
        headers: {
          Accept: "application/json",
          Authorization: this.token,
        },
      };

      axios
        .post(url, formData, config)
        .then((response) => {
          this.$store.state.transacao.status = "success";
          this.$store.state.transacao.mensagem = "Marca deletada com sucesso";
          this.carregarLista();
        })
        .catch((errors) => {
          this.$store.state.transacao.status = "danger";
          this.$store.state.transacao.mensagem = errors.response.data.error;
        });
    },
    pesquisar() {
      // console.log(this.busca)
      let filtro = "";

      for (let chave in this.busca) {
        if (this.busca[chave]) {
          if (filtro != "") {
            filtro += ";";
          }
          filtro += chave + ":like:%" + this.busca[chave] + "%";
        }
        // console.log(filtro)
      }
      if (filtro != "") {
        this.urlPaginacao = "page=1";
        this.urlBusca = "&filtro=" + filtro;
      } else {
        this.urlBusca = "";
      }

      this.carregarLista();
    },
    paginacao(l) {
      if (l.url) {
        // this.urlBase = l.url;
        this.urlPaginacao = l.url.split("?")[1];
        // console.log('url da paginacao', l.url.split('?')[1])
        this.carregarLista();
      }
    },
    carregarLista() {
      let url = this.urlBase + "?" + this.urlPaginacao + this.urlBusca;
      // console.log(url)
      axios
        .get(url, {
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            Authorization: this.token,
          },
        })
        .then((response) => {
          this.marcas = response.data;
          //   console.log(this.marcas);
        })
        .then((errors) => {
          // console.log(errors)
        });
    },
    carregarImagem(e) {
      this.arquivoImagem = e.target.files;
    },
    salvar() {
      let formData = new FormData();
      formData.append("nome", this.nomeMarca);
      formData.append("imagem", this.arquivoImagem[0]);

      let config = {
        headers: {
          "Content-Type": "multipart/form-data",
          Accept: "application/json",
          Authorization: this.token,
        },
      };

      axios
        .post(this.urlBase, formData, config)
        .then((response) => {
          this.status = "adicionado";
          this.responseApi = response;
          this.carregarLista();
        })
        .catch((errors) => {
          this.status = "erro";
          this.responseApi = errors.response;
        });
    },
  },

  computed: {
    token() {
      let token = document.cookie.split(";").find((x) => {
        return x.includes("token=");
      });

      token = "Bearer " + token.split("=")[1];

      return token;
    },
  },
  mounted() {
    this.carregarLista();
  },
};
</script>


<style>
</style>
