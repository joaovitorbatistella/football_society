import Cookies from 'js-cookie'

export default function({ store, redirect }) {
  let token = Cookies.get('jwt-token')   
  // If the user is not authenticated, redirect for login
  if (!token) {
    return redirect('/login')
  } else {
    console.log("You're authenticated")
  }
}
