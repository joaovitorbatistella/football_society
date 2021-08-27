import { deburr } from 'lodash'

const defaultField = {
  value: '',
  error: [],
  rules: []
}

const defaultAutoSelect = {
  value: undefined, // 'undefined'
  items: [],
  isLoading: false,
  disabled: true
}

const typeOf = obj => {
  return {}.toString
    .call(obj)
    .split(' ')[1]
    .slice(0, -1)
    .toLowerCase()
}

const filled = (v, bool = false) => {
  switch (typeOf(v)) {
    case 'null':
    case 'undefined':
      return bool ? v : false
    case 'array':
      return v.length ? (bool ? v : true) : false
    case 'string':
      return v !== '' ? (bool ? v : true) : false
    case 'object':
      return Object.keys(v).length ? (bool ? v : true) : false
    default:
      return v ? (bool ? v : true) : false
  }
  // if (v !== null && v !== undefined && v !== {} && v !== [])
  //   return bool ? v : true
  // return bool ? null : false
}

const strSlug = (str, replace = '-') =>
  deburr(str)
    .replace(/\s/g, replace)
    .toLowerCase()

const getRandomArbitrary = function(min, max) {
  min = Math.ceil(min)
  max = Math.floor(max)
  return Math.floor(Math.random() * (max - min)) + min
}

const dateAsUTC = function(date) {
  return new Date(
    Date.UTC(
      date.getFullYear(),
      date.getMonth(),
      date.getDate(),
      date.getHours(),
      date.getMinutes(),
      date.getSeconds()
    )
  )
}

const getRandomString = function(size = 10, numbers = true, prefix = '') {
  const str = 'abcdefghijklmnopqrstuvwxyz0123456789'
  const limit = numbers ? str.length : str.length - 10
  let ret = [prefix]
  while (size--) {
    ret.push(str[getRandomArbitrary(0, limit)])
  }
  return ret.join('')
}

const errorMessage = (xxx = '') => {
  console.log('')
}

const invalidMessage = (xxx = '') => {
  console.log('')
}

const anyStepObject = (name = 'Qualuqe', id = -1) => {
  return {
    id,
    name,
    extra: { icon: 'done_all', color: 'green' }
  }
}

const getASById = function(list, id) {
  return list.find(el => {
    return el.id === id
  })
}

const expModeList = [{ id: 'binary' }, { id: 'linear' }]
const getExpMode = index => {
  return expModeList.find(el => el.id === index)
}

export {
  typeOf,
  filled,
  getRandomArbitrary,
  anyStepObject,
  defaultField,
  getASById,
  defaultAutoSelect,
  strSlug,
  errorMessage,
  invalidMessage,
  getRandomString,
  expModeList,
  getExpMode,
  dateAsUTC
}
