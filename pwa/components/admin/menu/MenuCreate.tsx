import {CreateGuesser, InputGuesser} from "@api-platform/admin";

const MenuCreate = (props:{props:any}) => (
  <CreateGuesser {...props}>
    <InputGuesser source={"name"} />
    <InputGuesser source={"parent"} />
    <InputGuesser source={"slug"} />
    <InputGuesser source={"path"} />
    <InputGuesser source={"isBottomMenu"} />
    <InputGuesser source={"isLeftMenu"} />
    <InputGuesser source={"isTopMenu"} />

    <InputGuesser source={"status"} />
    <InputGuesser source={"type"} />
  </CreateGuesser>
);

export default MenuCreate;
