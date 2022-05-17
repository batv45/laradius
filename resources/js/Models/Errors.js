
export default class Errors {
    constructor(errs) {
        this.errors = errs
        console.log(this.errors)
    }
    get errs(){
        return this.errors;
    }
}
