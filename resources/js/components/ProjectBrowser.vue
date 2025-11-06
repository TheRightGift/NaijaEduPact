<template>
    <div>
        <div class="row">
            <div class="input-field col s12 m6">
                <i class="material-icons prefix">search</i>
                <input type="text" id="search" v-model="searchTerm" @input="updateFilters">
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
                        <p><strong>{{ project.university.name }}</strong></p>
                        <br>
                        <div class="progress">
                            <div class="determinate" :style="{ width: getProgress(project) + '%' }"></div>
                        </div>
                        <p>₦{{ formatCurrency(project.current_amount) }} raised of ₦{{ formatCurrency(project.goal_amount) }}</p>
                    </div>
                    <div class="card-action">
                         <a v-if="!isLoggedIn" :href="`${appUrl}/projects/${project.slug}`" class="indigo-text">View Project Details</a>
                        <a v-else :href="`#donateModal-${project.id}`" class="btn-flat indigo-text modal-trigger">
                            Donate Now
                        </a>
                    </div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">{{ project.title }}<i class="material-icons right">close</i></span>
                        <div v-html="project.description"></div>
                    </div>
                </div>

                <div :id="`donateModal-${project.id}`" class="modal">
                    <form :action="donateUrl" method="POST">
                        <input type="hidden" name="_token" :value="csrfToken">
                        <input type="hidden" name="project_id" :value="project.id">

                        <div class="modal-content">
                            <h4>Donate to {{ project.title }}</h4>
                            <div class="input-field">
                                <input type="number" name="amount" :id="`amount-${project.id}`" :min="minDonation" required>
                                <label :for="`amount-${project.id}`">Amount (NGN)</label>
                                <span class="helper-text">Minimum donation is ₦{{ minDonation }} (approx. $1 USD)</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="#!" class="modal-close waves-effect waves-grey btn-flat">Cancel</a>
                            <button type="submit" class="btn waves-effect waves-light indigo">
                                Proceed to Payment
                            </button>
                        </div>
                    </form>
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
        isLoggedIn: {
            type: Boolean,
            default: false
        },
        appUrl: { // <-- App URL prop
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
            defaultImageUrl: 'https://via.placeholder.com/400x300.png?text=Project+Image',
            csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            donateUrl: '/donate/start', // This is relative to the appUrl
            minDonation: 1450
        };
    },
    methods: {
        async fetchProjects() {
            this.loading = true;
            try {
                // Use the appUrl to make the API call
                const response = await axios.get(`${this.appUrl}/api/projects`);
                this.projects = response.data;
                this.filteredProjects = this.projects;
            } catch (error) {
                console.error("There was an error fetching the projects:", error);
            } finally {
                this.loading = false;
            }
        },
        updateFilters() {
            const term = this.searchTerm.toLowerCase();
            this.filteredProjects = this.projects.filter(project =>
                project.title.toLowerCase().includes(term) ||
                project.university.name.toLowerCase().includes(term)
            );
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
            
            let imagePath = '';
            // Handle incorrect paths that already contain 'storage/'
            if (path.startsWith('storage/')) {
                imagePath = path;
            } else {
                imagePath = `storage/${path}`;
            }
            // Prepend the appUrl prop to create the full, absolute URL
            return `${this.appUrl}/${imagePath}`;
        },
        setDefaultImage(event) {
            event.target.src = this.defaultImageUrl;
        },
        initializeModals() {
            this.$nextTick(() => {
                setTimeout(() => {
                    var elems = document.querySelectorAll('.modal');
                    M.Modal.init(elems);
                }, 500);
            });
        }
    },
    watch: {
        filteredProjects() {
            this.initializeModals();
        }
    },
    mounted() {
        this.fetchProjects();
    }
}
</script>