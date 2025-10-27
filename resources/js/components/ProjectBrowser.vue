<template>
    <div>
        <div class="row">
            <div class="input-field col s12 m6">
                <i class="material-icons prefix">search</i>
                <input type="text" id="search" v-model="searchTerm">
                <label for="search">Search by project title...</label>
            </div>
        </div>

        <div v-if="loading" class="center-align">
            <div class="preloader-wrapper big active">
                <div class="spinner-layer spinner-blue-only">
                    <div class="circle-clipper left"><div class="circle"></div></div>
                    <div class="gap-patch"><div class="circle"></div></div>
                    <div class="circle-clipper right"><div class="circle"></div></div>
                </div>
            </div>
        </div>

        <div v-else class="row">
            <div v-for="project in filteredProjects" :key="project.id" class="col s12 m6 l4">
                <div class="card">
                    <div class="card-image">
                        <img :src="project.cover_image_path || 'https://via.placeholder.com/400x300.png?text=Project+Image'">
                    </div>
                    <div class="card-content">
                        <span class="card-title activator grey-text text-darken-4">{{ project.title }}<i class="material-icons right">more_vert</i></span>
                        <p><strong>{{ project.university.name }}</strong></p>
                        <br>
                        <div class="progress">
                            <div class="determinate" :style="{ width: getProgress(project) + '%' }"></div>
                        </div>
                        <p>₦{{ formatCurrency(project.current_amount) }} raised of ₦{{ formatCurrency(project.goal_amount) }}</p>
                    </div>
                    <div class="card-action">
                        <a href="#" class="indigo-text">Donate Now</a>
                    </div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">{{ project.title }}<i class="material-icons right">close</i></span>
                        <p>{{ project.description }}</p>
                    </div>
                </div>
            </div>
            <div v-if="filteredProjects.length === 0" class="col s12">
                 <p class="center-align">No projects match your search criteria.</p>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            projects: [],
            searchTerm: '',
            loading: true,
        };
    },
    computed: {
        filteredProjects() {
            if (!this.searchTerm) {
                return this.projects;
            }
            return this.projects.filter(project =>
                project.title.toLowerCase().includes(this.searchTerm.toLowerCase())
            );
        }
    },
    methods: {
        async fetchProjects() {
            this.loading = true;
            try {
                const response = await axios.get('/api/projects');
                this.projects = response.data;
            } catch (error) {
                console.error("There was an error fetching the projects:", error);
            } finally {
                this.loading = false;
            }
        },
        getProgress(project) {
            if (project.goal_amount > 0) {
                return (project.current_amount / project.goal_amount) * 100;
            }
            return 0;
        },
        formatCurrency(amount) {
            return Number(amount).toLocaleString();
        }
    },
    mounted() {
        this.fetchProjects();
    }
}
</script>