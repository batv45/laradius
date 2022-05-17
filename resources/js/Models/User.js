
export default class User {
    constructor(data) {
        for (let dat in data) {
            this[dat] = data[dat];
        }
    }
    get role_names(){
        return this.roles.map(e => e.name).join(", ");
    }
    get role_descriptions(){
        return this.roles.map(e => e.description).join(", ");
    }
}
