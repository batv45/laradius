import User from "../User";

export default class UserPaginate {
    constructor(data) {
        for (let dat in data) {
            if(dat === 'data'){
                this['data'] = [];
                for (let da of data[dat]) {
                    this['data'].push(new User(da))
                }
            }else{
                this[dat] = data[dat];
            }
        }
    }
}
