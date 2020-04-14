const app = new Vue({
    el: '#vueApp',
    data() {
        return {
            dataLoaded: false,
            vErrors: [],
            allData: [],
            updateID: 0,
            name: '',
            day: ''
        };
    },
    mounted: function () {
        this.getAllData();
    },
    methods: {
        getAllData() {
            this.dataLoaded = false;
            axios.get('/live/meetings').then(response => {
                this.allData = response.data;
            }).catch(error => {
                console.log(error);
            }).finally(() => this.dataLoaded = true);
        },
        createData() {
            axios.post('/live/meeting/store', this.setFormData(),
                {headers: {'Content-Type': 'multipart/form-data'}}
            ).then(response => {
                let result = response.data;
                if (result.status === 'success') {
                    this.resetFormData();
                    this.allData = result.data;
                    $('#formModal').modal('toggle');
                    this.successAlert('Meeting Created!');
                } else {
                    this.vErrors = result.errors;
                }
            });
        },
        editData(index) {    
            let updateData = this.allData[index];
            if (updateData) {
                this.updateID = updateData.id;
                this.name = updateData.name;
                this.day = updateData.day;
                this.vErrors = false;
            }
        },
        updateData() {
            axios.post('/live/meeting/update/', this.setFormData(),
                {headers: {'Content-Type': 'multipart/form-data'}}
            ).then(response => {
                let result = response.data;
                if (result.status === 'success') {
                    this.resetFormData();
                    if (result.data) {
                        this.allData = result.data;
                    }
                    $('#formModal').modal('toggle');
                    this.successAlert('Meeting Updated!');
                }else {
                    this.vErrors = result.errors;
                }
            });
        },
        deleteData(itemID) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    axios.delete('/live/meeting/delete/' + itemID).then(response => {
                        if (response.data) {
                            this.allData = response.data;
                        }
                        this.successAlert('Meeting Deleted!');
                    }).catch(error => {
                        console.log(error);
                    });
                }
            });
        },
        setFormData() {
            let dataForm = new FormData();
            if(this.updateID){
                dataForm.append('update_id', this.updateID);
            }
            dataForm.append('name', this.name);
            dataForm.append('day', this.day);
            return dataForm;
        },
        resetFormData() {
            this.name = '';
            this.day = '';
            this.vErrors = false;
            this.updateID = 0;
        },
        successAlert(message = '') {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 1500
            });
        }
    }
});

