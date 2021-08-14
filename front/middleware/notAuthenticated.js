import Cookies from 'js-cookie'

export default function({ redirect }) {
  let token = Cookies.get('jwt-token')   
  // If the user is not authenticated, redirect for login
  if (token) {
    return redirect('/dash')
  } else {
    console.log("You're not authenticated")
  }
}
