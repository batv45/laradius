
import User from "./User";

export default class UserCollection {
    constructor(userlist) {
        console.log(userlist)
        this.data = [];
        for (let dat in userlist) {
            this.data.push(new User(userlist[dat]));

        }
    }

    get getArray(){
        return this.data
    }
}
