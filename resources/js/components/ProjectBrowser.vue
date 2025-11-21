<template>
    <div>
        <div class="row">
            <div class="input-field col s12 m6">
                <i class="material-icons prefix">search</i>
                <input type="text" id="search" v-model="searchTerm">
                <label for="search">Search by project title or university...</label>
            </div>
        </div>

        <div v-if="loading" class="center-align" style="padding-top: 50px;">
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
                        <img :src="getImageUrl(project.cover_image_path)" @error="setDefaultImage">
                    </div>
                    <div class="card-content">
                        <span class="card-title activator grey-text text-darken-4">{{ project.title }}<i class="material-icons right">more_vert</i></span>
                        <p>
                            <a :href="`${appUrl}/universities/${project.university.slug}`" class="indigo-text">
                                <strong>{{ project.university.name }}</strong>
                            </a>
                        </p>
                        <br>
                        <div class="progress">
                            <div class="determinate" :style="{ width: getProgress(project) + '%' }"></div>
                        </div>
                        <p>₦{{ formatCurrency(project.current_amount) }} raised of ₦{{ formatCurrency(project.goal_amount) }}</p>
                    </div>
                    <div class="card-action">
                         <a :href="`${appUrl}/projects/${project.slug}`" class="indigo-text">View Project Details</a>
                    </div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">{{ project.title }}<i class="material-icons right">close</i></span>
                        <div v-html="project.description"></div>
                    </div>
                </div>
                </div>
            
            <div v-if="!loading && filteredProjects.length === 0" class="col s12">
                 <div class="card-panel">
                    <p class="center-align">No projects match your search criteria.</p>
                 </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        // We only need the appUrl prop now
        appUrl: { 
            type: String,
            required: true
        }
    },
    data() {
        return {
            projects: [],
            filteredProjects: [],
            searchTerm: '',
            loading: true,
            defaultImageUrl: 'https://placehold.co/600x400/black/white?text=No Cover Image',
            // All donation-related data has been REMOVED
        };
    },
    computed: {
        // This automatically filters the list when searchTerm changes
        filteredProjects() {
            if (!this.searchTerm) {
                return this.projects;
            }
            const term = this.searchTerm.toLowerCase();
            return this.projects.filter(project =>
                project.title.toLowerCase().includes(term) ||
                project.university.name.toLowerCase().includes(term)
            );
        }
    },
    methods: {
        async fetchProjects() {
            this.loading = true;
            try {
                const response = await axios.get(`${this.appUrl}/api/projects`);
                this.projects = response.data;
            } catch (error) {
                console.error("There was an error fetching the projects:", error);
            } finally {
                this.loading = false;
            }
        },
        getProgress(project) {
            if (project.goal_amount > 0) {
                let progress = (project.current_amount / project.goal_amount) * 100;
                return Math.min(progress, 100);
            }
            return 0;
        },
        formatCurrency(amount) {
            if (!amount) return '0.00';
            return Number(amount).toLocaleString('en-NG', { minimumFractionDigits: 2 });
        },
        getImageUrl(path) {
            if (!path) {
                return this.defaultImageUrl;
            }
            let imagePath = path.startsWith('storage/') ? path : `storage/${path}`;
            return `${this.appUrl}/${imagePath}`;
        },
        setDefaultImage(event) {
            event.target.src = this.defaultImageUrl;
        },
        // The initializeModals method and the 'watch' block have been REMOVED
    },
    mounted() {
        this.fetchProjects();
    }
}
</script>