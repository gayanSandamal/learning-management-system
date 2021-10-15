import baseAPIExicuter from './baseAPIExicuter'

export default {

    async getUserClasses (userName, token, userId, offset, perPage, sucessCallback, errorCallback) {
        const obj = {
            username: userName,
            token: token,
            enrollment_month: "2021-05-01 00:00:00",
            offset: offset,
            per_page: perPage,
            state: 1,
            user_id: userId,
        }
        const url = 'http://qa.akurata.lk/web-api/api/get-enrolled-classes-full'
        return await baseAPIExicuter.tokenPost(url, obj, sucessCallback, errorCallback)
    }
}

// approval_state: "approved"
// assignee_firstname: "instructor"
// assignee_id: 53
// assignee_lastname: "test"
// assignee_username: "instructor"
// cat_name: "Maths"
// cat_slug: "grade-1-5-grade-1-maths"
// enrolled_date: "2021-05-15 05:37:17"
// fee: 500
// id: 36
// item_id: 73
// post_id: 53
// post_name: "Lesson test"
// post_slug: "lesson-test-2021-05-15-16-51"