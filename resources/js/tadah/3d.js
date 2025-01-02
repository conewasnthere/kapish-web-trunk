import * as THREE from 'three'
import { OBJLoader } from 'three/examples/jsm/loaders/OBJLoader'
import { MTLLoader } from 'three/examples/jsm/loaders/MTLLoader'
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls'

tadah.threeDee = {}

tadah.threeDee.draw = async (container, assetType, assetId, width, height) => {
    // load assets
    let assets = await fetch(window.location.origin + `/api/thumbnail?id=${assetId}&type=${assetType}&3d=true`)
    assets = await assets.json()
    if (assets.status != 0) {
        return false
    }

    let obj = assets.result['3d'].obj.split('/').pop()
    let mtl = assets.result['3d'].mtl.split('/').pop()

    var camera, scene, renderer, player, id, pivot, grabbing = null
    
    function render()
    {
        renderer.render(scene, camera)
    }

    function animate()
    {
        id = requestAnimationFrame(animate)

        if (player != null && !grabbing)  {
            pivot.rotation.y += 0.01
        }

        render()
    }

    pivot = new THREE.Group()

    renderer = new THREE.WebGLRenderer({
        alpha: true,
        antialias: true,
        sortObjects: false
    })
    renderer.setPixelRatio(window.devicePixelRatio)
    renderer.setSize(width, height)

    scene = new THREE.Scene()
    camera = new THREE.PerspectiveCamera(70, width / height, 0.1, 100)
    camera.position.z = 7
    scene.add(camera)
    
    // lighting
    scene.add(new THREE.AmbientLight(0x878780))
    
    let light1 = new THREE.DirectionalLight(0xACACAC)
    light1.position.set(-.671597898, .671597898, .312909544).normalize() // wtf, roblox
    scene.add(light1)
    
    let light2 = new THREE.DirectionalLight(0x444444)
    light2.position.set((new THREE.Vector3).copy(light1.position).negate().normalize())
    scene.add(light2)
    
    // why must you hardcode everything
    new MTLLoader().setPath($(location).attr('protocol') + '//cdn.' + $(location).attr('hostname')  + '/').load(mtl, (materials) => {
        materials.preload()

        new OBJLoader().setPath($(location).attr('protocol') +  '//cdn.'  + $(location).attr('hostname')  +  '/').setMaterials(materials).load(obj, (object) => {
            player = object

            let box = new THREE.Box3().setFromObject(player)
            let controls = new OrbitControls(camera, renderer.domElement)
            controls.target.copy(player.position)
    
            let center = new THREE.Vector3()
            player.position.multiplyScalar(-1)
    
            box.getCenter(center)
            player.position.sub(center)
    
            // pivot will center
            scene.add(pivot)
            pivot.add(player)
        })
    })

    $(container).append(renderer.domElement)
    $(renderer.domElement).css("display", "").addClass("d-none").attr("id", "three-dee-canvas")

    $(renderer.domElement).on("mousedown touchstart", () => { grabbing = true })
    $(renderer.domElement).on("mouseup touchend", () => { grabbing = false })

    animate()
    tadah.threeDee.id = id
}

tadah.threeDee.cancel = () => {
    cancelAnimationFrame(tadah.threeDee.id)
}